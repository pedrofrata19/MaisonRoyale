<?php

require_once __DIR__ . "/../../config/database.php";

class Quarto
{
    private $pdo;

    public function __construct()
    {
        global $pdo;
        $this->pdo = $pdo;
    }

    public function listar($filtros = [])
    {
        $sql = "SELECT * FROM quartos WHERE ativo = 1";
        $parametros = [];

        if (!empty($filtros["categoria"])) {
            $sql .= " AND categoria = ?";
            $parametros[] = $filtros["categoria"];
        }

        if (!empty($filtros["hospedes"])) {
            $sql .= " AND capacidade >= ?";
            $parametros[] = (int) $filtros["hospedes"];
        }

        $checkIn = trim($filtros["check_in"] ?? "");
        $checkOut = trim($filtros["check_out"] ?? "");

        if ($checkIn !== "" && $checkOut !== "") {
            $sql .= " AND id NOT IN (
                        SELECT quarto_id FROM reservas
                        WHERE status = 'Confirmada'
                          AND check_in < ?
                          AND check_out > ?
                      )";
            $parametros[] = $checkOut;
            $parametros[] = $checkIn;
        }

        $sql .= " ORDER BY id DESC";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($parametros);

        return $stmt->fetchAll();
    }

    public function listarDisponiveis($checkIn, $checkOut, $hospedes)
    {
        return $this->listar([
            "check_in" => $checkIn,
            "check_out" => $checkOut,
            "hospedes" => $hospedes,
        ]);
    }

    public function buscarPorId($id)
    {
        $sql = "SELECT * FROM quartos WHERE id = ? LIMIT 1";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([(int) $id]);

        return $stmt->fetch();
    }

    public function cadastrar($dados)
    {
        $sql = "INSERT INTO quartos
                (nome, categoria, descricao, capacidade, preco, imagem)
                VALUES (?, ?, ?, ?, ?, ?)";

        try {
            $stmt = $this->pdo->prepare($sql);

            return $stmt->execute([
                $dados["nome"],
                $dados["categoria"],
                $dados["descricao"],
                (int) $dados["capacidade"],
                (float) $dados["preco"],
                $dados["imagem"],
            ]);
        } catch (PDOException $erro) {
            return false;
        }
    }

    public function editar($id, $dados)
    {
        $sql = "UPDATE quartos SET
                    nome = ?,
                    categoria = ?,
                    descricao = ?,
                    capacidade = ?,
                    preco = ?,
                    imagem = ?
                WHERE id = ?";

        try {
            $stmt = $this->pdo->prepare($sql);

            return $stmt->execute([
                $dados["nome"],
                $dados["categoria"],
                $dados["descricao"],
                (int) $dados["capacidade"],
                (float) $dados["preco"],
                $dados["imagem"],
                (int) $id,
            ]);
        } catch (PDOException $erro) {
            return false;
        }
    }

    public function excluir($id)
    {
        try {
            $sql = "DELETE FROM quartos WHERE id = ?";
            $stmt = $this->pdo->prepare($sql);

            return $stmt->execute([(int) $id]);
        } catch (PDOException $erro) {
            return false;
        }
    }

    public function categorias()
    {
        $sql = "SELECT DISTINCT categoria FROM quartos ORDER BY categoria";

        return $this->pdo->query($sql)->fetchAll(PDO::FETCH_COLUMN);
    }
}
