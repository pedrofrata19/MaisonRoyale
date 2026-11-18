<?php


session_start();

require_once __DIR__ . "/config/database.php";



function e($valor)
{
    return htmlspecialchars((string) $valor, ENT_QUOTES, "UTF-8");
}

function mensagem()
{
    if (empty($_SESSION["flash"])) {
        return "";
    }

    $flash = $_SESSION["flash"];
    unset($_SESSION["flash"]);

    $classe = ($flash["tipo"] ?? "sucesso") === "erro" ? "erro" : "sucesso";

    return '<div class="mensagem ' . $classe . '">' . e($flash["texto"]) . '</div>';
}

function redirecionar($acao, $texto = "", $tipo = "sucesso")
{
    if ($texto !== "") {
        $_SESSION["flash"] = ["tipo" => $tipo, "texto" => $texto];
    }


    header("Location: index.php?acao=" . $acao);
    exit;
}

function old($campo, $padrao = "")
{
    $valor = $_SESSION["old"][$campo] ?? $padrao;
    unset($_SESSION["old"][$campo]);
    return e($valor);
}

function urlImagem($caminho)
{
    $caminho = trim((string) $caminho);

    if ($caminho === "") {
        return "";
    }
    if (preg_match("#^https?://#i", $caminho) || strpos($caminho, "/") === 0) {
        return $caminho;
    }

    return "/Maison_Royale/" . ltrim($caminho, "/");
}

function usuarioLogado()
{
    return isset($_SESSION["usuario_id"]);
}

function eAdmin()
{
    return usuarioLogado() && ($_SESSION["perfil"] ?? "") === "admin";
}

function exigirLogin()
{
    if (!usuarioLogado()) {
        redirecionar("login", "Faca login para acessar essa area.", "erro");
    }
}

function exigirAdmin()
{
    exigirLogin();

    if (!eAdmin()) {
        redirecionar("painel", "Area restrita ao administrador.", "erro");
    }
}


$acao = $_GET["acao"] ?? "login";

switch ($acao) {

  
    case "login":
        require __DIR__ . "/app/views/auth/login.php";
        break;

    case "cadastro_usuario":
        require __DIR__ . "/app/views/auth/cadastro.php";
        break;

    case "salvar_usuario":
        require_once __DIR__ . "/app/controllers/AuthController.php";
        (new AuthController())->cadastrar();
        break;

    case "entrar":
        require_once __DIR__ . "/app/controllers/AuthController.php";
        (new AuthController())->entrar();
        break;

    case "sair":
        require_once __DIR__ . "/app/controllers/AuthController.php";
        (new AuthController())->sair();
        break;

    case "painel":
        exigirLogin();
        require __DIR__ . "/app/views/painel.php";
        break;

    case "quartos":
        require_once __DIR__ . "/app/controllers/QuartoController.php";
        $controller = new QuartoController();
        $quartos = $controller->listar($_GET);
        $categorias = $controller->categorias();
        require __DIR__ . "/app/views/quartos/quartos.php";
        break;

    case "reservar":
        exigirLogin();
        require_once __DIR__ . "/app/controllers/QuartoController.php";
        $controller = new QuartoController();
        $quartoId = $_GET["id"] ?? ($_SESSION["old"]["quarto_id"] ?? 0);
        $quarto = $controller->buscarPorId($quartoId);

        if (!$quarto) {
            redirecionar("quartos", "Quarto nao encontrado.", "erro");
        }

        require __DIR__ . "/app/views/reservas/reserva_form.php";
        break;

    case "salvar_reserva":
        exigirLogin();
        require_once __DIR__ . "/app/controllers/ReservaController.php";
        (new ReservaController())->salvar();
        break;

    case "minhas_reservas":
        exigirLogin();
        require_once __DIR__ . "/app/controllers/ReservaController.php";
        $controller = new ReservaController();
        $reservas = $controller->minhasReservas();
        require __DIR__ . "/app/views/reservas/minhas_reservas.php";
        break;

    case "reserva_detalhe":
        exigirLogin();
        require_once __DIR__ . "/app/controllers/ReservaController.php";
        $controller = new ReservaController();
        $reserva = $controller->detalhe($_GET["id"] ?? 0);

        if (!$reserva || (!eAdmin() && $reserva["usuario_id"] != $_SESSION["usuario_id"])) {
            redirecionar("minhas_reservas", "Reserva nao encontrada.", "erro");
        }

        require __DIR__ . "/app/views/reservas/reserva_detalhe.php";
        break;

    case "cancelar_reserva":
        exigirLogin();
        require_once __DIR__ . "/app/controllers/ReservaController.php";
        (new ReservaController())->cancelar();
        break;

   
    case "admin_quartos":
        exigirAdmin();
        require_once __DIR__ . "/app/controllers/QuartoController.php";
        $controller = new QuartoController();
        $quartos = $controller->listar();
        require __DIR__ . "/app/views/admin/quartos.php";
        break;

    case "cadastrar_quarto":
        exigirAdmin();
        $quarto = null;
        require __DIR__ . "/app/views/admin/quarto_form.php";
        break;

    case "editar_quarto":
        exigirAdmin();
        require_once __DIR__ . "/app/controllers/QuartoController.php";
        $controller = new QuartoController();
        $quarto = $controller->buscarPorId($_GET["id"] ?? 0);

        if (!$quarto) {
            redirecionar("admin_quartos", "Quarto nao encontrado.", "erro");
        }

   
        if (!empty($_SESSION["old"]["id"]) && (int) $_SESSION["old"]["id"] === (int) $quarto["id"]) {
            foreach (["nome", "categoria", "descricao", "capacidade", "preco", "imagem"] as $campo) {
                if (array_key_exists($campo, $_SESSION["old"])) {
                    $quarto[$campo] = $_SESSION["old"][$campo];
                }
            }
            unset($_SESSION["old"]);
        }

        require __DIR__ . "/app/views/admin/quarto_form.php";
        break;

    case "salvar_quarto":
        exigirAdmin();
        require_once __DIR__ . "/app/controllers/QuartoController.php";
        (new QuartoController())->salvar();
        break;

    case "excluir_quarto":
        exigirAdmin();
        require_once __DIR__ . "/app/controllers/QuartoController.php";
        (new QuartoController())->excluir();
        break;

    case "admin_reservas":
        exigirAdmin();
        require_once __DIR__ . "/app/controllers/ReservaController.php";
        $controller = new ReservaController();
        $reservas = $controller->listarTodas();
        require __DIR__ . "/app/views/admin/reservas.php";
        break;

    case "admin_usuarios":
        exigirAdmin();
        require_once __DIR__ . "/app/controllers/UsuarioController.php";
        $controller = new UsuarioController();
        $usuarios = $controller->listar();
        require __DIR__ . "/app/views/admin/usuarios.php";
        break;

    default:
        http_response_code(404);
        echo "Pagina nao encontrada.";
}
