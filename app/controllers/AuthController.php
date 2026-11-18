<?php

require_once __DIR__ . "/../models/Usuario.php";

class AuthController
{
    private $model;

    public function __construct()
    {
        $this->model = new Usuario();
    }

    public function cadastrar()
    {
        $nome = trim($_POST["nome"] ?? "");
        $email = trim($_POST["email"] ?? "");
        $cpfDigitado = trim($_POST["cpf"] ?? "");
        $cpf = preg_replace("/\D/", "", $cpfDigitado);
        $senha = $_POST["senha"] ?? "";
        $confirmar = $_POST["confirmar_senha"] ?? "";

        $_SESSION["old"] = [
            "nome" => $nome,
            "email" => $email,
            "cpf" => $cpfDigitado,
        ];

        $erros = [];

        if (mb_strlen($nome) < 3) {
            $erros[] = "Informe um nome com pelo menos 3 caracteres.";
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $erros[] = "Informe um e-mail valido.";
        }
        if (strlen($cpf) !== 11 || preg_match("/^(\d)\1{10}$/", $cpf)) {
            $erros[] = "Informe um CPF valido com 11 digitos.";
        }
        if (strlen($senha) < 6) {
            $erros[] = "A senha deve ter pelo menos 6 caracteres.";
        }
        if ($senha !== $confirmar) {
            $erros[] = "As senhas nao coincidem.";
        }

        if (!$erros && $this->model->buscarPorEmail($email)) {
            $erros[] = "Ja existe uma conta com esse e-mail.";
        }
        if (!$erros && $this->model->buscarPorCpf($cpf)) {
            $erros[] = "Ja existe uma conta com esse CPF.";
        }

        if ($erros) {
            redirecionar("cadastro_usuario", implode(" ", $erros), "erro");
        }

        if (!$this->model->cadastrar($nome, $email, $cpf, $senha, "cliente")) {
            redirecionar("cadastro_usuario", "Nao foi possivel criar a conta. Tente novamente.", "erro");
        }

        unset($_SESSION["old"]);
        redirecionar("login", "Conta criada com sucesso. Faca login para continuar.");
    }

    public function entrar()
    {
        $email = trim($_POST["email"] ?? "");
        $senha = $_POST["senha"] ?? "";

        $_SESSION["old"] = ["email" => $email];

        if ($email === "" || $senha === "") {
            redirecionar("login", "Informe e-mail e senha.", "erro");
        }

        $usuario = $this->model->buscarPorEmail($email);

        if (!$usuario || !password_verify($senha, $usuario["senha"])) {
            redirecionar("login", "E-mail ou senha invalidos.", "erro");
        }

        session_regenerate_id(true);

        $_SESSION["usuario_id"] = $usuario["id"];
        $_SESSION["nome"] = $usuario["nome"];
        $_SESSION["perfil"] = $usuario["perfil"];
        unset($_SESSION["old"]);

        redirecionar("painel", "Bem-vindo, " . $usuario["nome"] . "!");
    }

    public function sair()
    {
        $_SESSION = [];
        session_destroy();
        session_start();

        redirecionar("login", "Voce foi desconectado.");
    }
}
