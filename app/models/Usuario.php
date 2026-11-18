<?php

require_once __DIR__ . "/../../config/database.php";

class Usuario
{
    private $pdo;

    public function __construct()
    {
        global $pdo;
        $this->pdo = $pdo;
    }

    public function cadastrar($nome, $email, $cpf, $senha, $perfil = "cliente")
    {
        $hash = password_hash($senha, PASSWORD_DEFAULT);

        $sql = "INSERT INTO usuarios (nome, email, cpf, senha, perfil)
                VALUES (?, ?, ?, ?, ?)";

        try {
            $stmt = $this->pdo->prepare($sql);

            return $stmt->execute([$nome, $email, $cpf, $hash, $perfil]);
        } catch (PDOException $erro) {
            return false;
        }
    }

    public function buscarPorEmail($email)
    {
        $sql = "SELECT * FROM usuarios WHERE email = ? LIMIT 1";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$email]);

        return $stmt->fetch();
    }

    public function buscarPorCpf($cpf)
    {
        $sql = "SELECT * FROM usuarios WHERE cpf = ? LIMIT 1";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$cpf]);

        return $stmt->fetch();
    }

    public function buscarPorId($id)
    {
        $sql = "SELECT * FROM usuarios WHERE id = ? LIMIT 1";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([(int) $id]);

        return $stmt->fetch();
    }

    public function listar()
    {
        $sql = "SELECT id, nome, email, cpf, perfil, criado_em
                FROM usuarios ORDER BY id DESC";

        return $this->pdo->query($sql)->fetchAll();
    }
}
