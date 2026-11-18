<?php

require_once __DIR__ . "/../models/Quarto.php";

class QuartoController
{
    private $model;

    public function __construct()
    {
        $this->model = new Quarto();
    }

    public function listar($filtros = [])
    {
        return $this->model->listar($filtros);
    }

    public function buscarPorId($id)
    {
        return $this->model->buscarPorId($id);
    }

    public function categorias()
    {
        return $this->model->categorias();
    }

    public function salvar()
    {
        exigirAdmin();

        $id = (int) ($_POST["id"] ?? 0);
        $imagemAtual = trim($_POST["imagem_atual"] ?? "");

        if (!preg_match('#^assets/img/quartos/[a-zA-Z0-9_-]+\.(jpg|jpeg|png|webp|gif)$#', $imagemAtual)) {
            $imagemAtual = "";
        }

        $dados = [
            "id" => $id,
            "nome" => trim($_POST["nome"] ?? ""),
            "categoria" => trim($_POST["categoria"] ?? ""),
            "descricao" => trim($_POST["descricao"] ?? ""),
            "capacidade" => $_POST["capacidade"] ?? "",
            "preco" => str_replace(",", ".", trim($_POST["preco"] ?? "")),
            "imagem" => $imagemAtual,
        ];

        $_SESSION["old"] = $dados;

        $erros = [];

        if (mb_strlen($dados["nome"]) < 3) {
            $erros[] = "Informe o nome do quarto.";
        }
        if ($dados["categoria"] === "") {
            $erros[] = "Informe a categoria.";
        }
        if (!filter_var($dados["capacidade"], FILTER_VALIDATE_INT) || (int) $dados["capacidade"] < 1) {
            $erros[] = "A capacidade deve ser um numero inteiro maior que zero.";
        }
        if (!is_numeric($dados["preco"]) || (float) $dados["preco"] <= 0) {
            $erros[] = "Informe um preco maior que zero.";
        }

        $rotaFormulario = $id ? "editar_quarto&id=" . $id : "cadastrar_quarto";

        if ($erros) {
            redirecionar($rotaFormulario, implode(" ", $erros), "erro");
        }

        $novoArquivo = null;
        $envio = $this->processarImagem($_FILES["imagem"] ?? null);

        if ($envio["erro"] !== "") {
            redirecionar($rotaFormulario, $envio["erro"], "erro");
        }

        if ($envio["caminho"] !== null) {
            $dados["imagem"] = $envio["caminho"];
            $novoArquivo = $envio["caminho"];
            $_SESSION["old"]["imagem"] = $dados["imagem"];
        }

        if ($id) {
            $ok = $this->model->editar($id, $dados);
            $mensagem = $ok ? "Quarto atualizado com sucesso." : "Nao foi possivel atualizar o quarto.";
        } else {
            $ok = $this->model->cadastrar($dados);
            $mensagem = $ok ? "Quarto cadastrado com sucesso." : "Nao foi possivel cadastrar o quarto.";
        }

        if (!$ok && $novoArquivo !== null) {
            $this->removerArquivo($novoArquivo);
        }

        unset($_SESSION["old"]);
        redirecionar("admin_quartos", $mensagem, $ok ? "sucesso" : "erro");
    }

    private function processarImagem($arquivo)
    {
        if (!is_array($arquivo) || ($arquivo["error"] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE) {
            return ["caminho" => null, "erro" => ""];
        }

        if ($arquivo["error"] !== UPLOAD_ERR_OK) {
            return ["caminho" => null, "erro" => "Nao foi possivel enviar a imagem. Tente novamente."];
        }

        if (($arquivo["size"] ?? 0) > 2 * 1024 * 1024) {
            return ["caminho" => null, "erro" => "A imagem deve ter no maximo 2 MB."];
        }

        $permitidos = [
            "image/jpeg" => "jpg",
            "image/png" => "png",
            "image/webp" => "webp",
            "image/gif" => "gif",
        ];

        $extensao = "";

        if (function_exists("finfo_open")) {
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mime = finfo_file($finfo, $arquivo["tmp_name"]);
            finfo_close($finfo);
            $extensao = $permitidos[$mime] ?? "";
        } elseif (function_exists("mime_content_type")) {
            $mime = mime_content_type($arquivo["tmp_name"]);
            $extensao = $permitidos[$mime] ?? "";
        } else {
            $info = @getimagesize($arquivo["tmp_name"]);
            $mapa = [
                IMAGETYPE_JPEG => "jpg",
                IMAGETYPE_PNG => "png",
                IMAGETYPE_WEBP => "webp",
                IMAGETYPE_GIF => "gif",
            ];
            $extensao = ($info && isset($mapa[$info[2]])) ? $mapa[$info[2]] : "";
        }

        if ($extensao === "") {
            return ["caminho" => null, "erro" => "Use uma imagem JPG, PNG, WEBP ou GIF."];
        }

        $pasta = __DIR__ . "/../../assets/img/quartos/";

        if (!is_dir($pasta) && !mkdir($pasta, 0755, true)) {
            return ["caminho" => null, "erro" => "Nao foi possivel criar a pasta de imagens."];
        }

        $nome = bin2hex(random_bytes(16)) . "." . $extensao;
        $destino = $pasta . $nome;

        if (!move_uploaded_file($arquivo["tmp_name"], $destino)) {
            return ["caminho" => null, "erro" => "Nao foi possivel salvar a imagem enviada."];
        }

        return ["caminho" => "assets/img/quartos/" . $nome, "erro" => ""];
    }

    private function removerArquivo($caminho)
    {
        $arquivo = __DIR__ . "/../../" . $caminho;

        if (is_file($arquivo)) {
            unlink($arquivo);
        }
    }

    public function excluir()
    {
        exigirAdmin();

        $id = (int) ($_POST["id"] ?? $_GET["id"] ?? 0);
        $quarto = $this->model->buscarPorId($id);

        if (!$quarto) {
            redirecionar("admin_quartos", "Quarto nao encontrado.", "erro");
        }

        if (!$this->model->excluir($id)) {
            redirecionar("admin_quartos", "Nao foi possivel excluir: o quarto possui reservas em seu historico.", "erro");
        }

        redirecionar("admin_quartos", "Quarto excluido com sucesso.");
    }
}
