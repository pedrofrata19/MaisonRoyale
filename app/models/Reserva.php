<?php

require_once __DIR__ . "/../../config/database.php";

class Reserva
{
    private $pdo;

    public function __construct()
    {
        global $pdo;
        $this->pdo = $pdo;
    }

    public function criar($usuarioId, $quartoId, $checkIn, $checkOut, $hospedes, $valorTotal)
    {
        $sql = "INSERT INTO reservas
                (usuario_id, quarto_id, check_in, check_out, hospedes, valor_total, status)
                VALUES (?, ?, ?, ?, ?, ?, 'Confirmada')";

        try {
            $stmt = $this->pdo->prepare($sql);

            return $stmt->execute([
                (int) $usuarioId,
                (int) $quartoId,
                $checkIn,
                $checkOut,
                (int) $hospedes,
                (float) $valorTotal,
            ]);
        } catch (PDOException $erro) {
            return false;
        }
    }

    public function listarPorUsuario($usuarioId)
    {
        $sql = "SELECT r.*, u.nome AS cliente, q.nome AS quarto, q.categoria
                FROM reservas r
                INNER JOIN usuarios u ON u.id = r.usuario_id
                INNER JOIN quartos q ON q.id = r.quarto_id
                WHERE r.usuario_id = ?
                ORDER BY r.id DESC";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([(int) $usuarioId]);

        return $stmt->fetchAll();
    }

    public function listarTodas()
    {
        $sql = "SELECT r.*, u.nome AS cliente, u.email, q.nome AS quarto, q.categoria
                FROM reservas r
                INNER JOIN usuarios u ON u.id = r.usuario_id
                INNER JOIN quartos q ON q.id = r.quarto_id
                ORDER BY r.id DESC";

        return $this->pdo->query($sql)->fetchAll();
    }

    public function buscarPorId($id)
    {
        $sql = "SELECT r.*, u.nome AS cliente, u.email, q.nome AS quarto, q.categoria, q.preco
                FROM reservas r
                INNER JOIN usuarios u ON u.id = r.usuario_id
                INNER JOIN quartos q ON q.id = r.quarto_id
                WHERE r.id = ? LIMIT 1";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([(int) $id]);

        return $stmt->fetch();
    }

    public function cancelar($id)
    {
        try {
            $sql = "UPDATE reservas SET status = 'Cancelada' WHERE id = ?";
            $stmt = $this->pdo->prepare($sql);

            return $stmt->execute([(int) $id]);
        } catch (PDOException $erro) {
            return false;
        }
    }

    public function conflito($quartoId, $checkIn, $checkOut)
    {
        $sql = "SELECT COUNT(*) FROM reservas
                WHERE quarto_id = ?
                  AND status = 'Confirmada'
                  AND check_in < ?
                  AND check_out > ?";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([(int) $quartoId, $checkOut, $checkIn]);

        return $stmt->fetchColumn() > 0;
    }

    public function totalConfirmado()
    {
        $sql = "SELECT COALESCE(SUM(valor_total), 0)
                FROM reservas WHERE status = 'Confirmada'";

        return $this->pdo->query($sql)->fetchColumn();
    }
}
