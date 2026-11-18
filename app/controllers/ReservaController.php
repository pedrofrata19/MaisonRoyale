<?php

require_once __DIR__ . "/../models/Reserva.php";
require_once __DIR__ . "/../models/Quarto.php";

class ReservaController
{
    private $model;
    private $quartoModel;

    public function __construct()
    {
        $this->model = new Reserva();
        $this->quartoModel = new Quarto();
    }

    public function minhasReservas()
    {
        return $this->model->listarPorUsuario($_SESSION["usuario_id"]);
    }

    public function listarTodas()
    {
        exigirAdmin();

        return $this->model->listarTodas();
    }

    public function detalhe($id)
    {
        return $this->model->buscarPorId($id);
    }

    public function salvar()
    {
        exigirLogin();

        $quartoId = (int) ($_POST["quarto_id"] ?? 0);
        $checkIn = trim($_POST["check_in"] ?? "");
        $checkOut = trim($_POST["check_out"] ?? "");
        $hospedes = (int) ($_POST["hospedes"] ?? 0);

        $_SESSION["old"] = [
            "quarto_id" => $quartoId,
            "check_in" => $checkIn,
            "check_out" => $checkOut,
            "hospedes" => $_POST["hospedes"] ?? "",
        ];

        $erros = [];
        $quarto = $this->quartoModel->buscarPorId($quartoId);

        if (!$quarto) {
            $erros[] = "Quarto invalido.";
        }

        $entrada = DateTime::createFromFormat("!Y-m-d", $checkIn);
        $saida = DateTime::createFromFormat("!Y-m-d", $checkOut);

        if (!$entrada || !$saida || $entrada->format("Y-m-d") !== $checkIn || $saida->format("Y-m-d") !== $checkOut) {
            $erros[] = "Informe datas de entrada e saida validas.";
        } else {
            if ($entrada >= $saida) {
                $erros[] = "A data de saida deve ser posterior a data de entrada.";
            }
            if ($entrada < new DateTime("today")) {
                $erros[] = "Nao e possivel reservar para datas passadas.";
            }

            if (!$erros && $this->model->conflito($quartoId, $checkIn, $checkOut)) {
                $erros[] = "Este quarto ja esta reservado para parte do periodo escolhido.";
            }
        }

        if ($hospedes < 1) {
            $erros[] = "Informe a quantidade de hospedes.";
        }

        if ($quarto && $hospedes > (int) $quarto["capacidade"]) {
            $erros[] = "O quarto comporta no maximo " . $quarto["capacidade"] . " hospedes.";
        }

        if ($erros) {
            redirecionar("reservar", implode(" ", $erros), "erro");
        }

        $diarias = $entrada->diff($saida)->days;
        $valorTotal = $diarias * (float) $quarto["preco"];

        if (!$this->model->criar($_SESSION["usuario_id"], $quartoId, $checkIn, $checkOut, $hospedes, $valorTotal)) {
            redirecionar("reservar", "Nao foi possivel concluir a reserva. Tente novamente.", "erro");
        }

        unset($_SESSION["old"]);
        redirecionar("minhas_reservas", "Reserva confirmada com sucesso!");
    }

    public function cancelar()
    {
        exigirLogin();

        $id = (int) ($_POST["id"] ?? $_GET["id"] ?? 0);
        $reserva = $this->model->buscarPorId($id);

        if (!$reserva) {
            redirecionar("minhas_reservas", "Reserva nao encontrada.", "erro");
        }

        if (!eAdmin() && $reserva["usuario_id"] != $_SESSION["usuario_id"]) {
            redirecionar("minhas_reservas", "Voce nao pode cancelar a reserva de outro cliente.", "erro");
        }

        if ($reserva["status"] !== "Confirmada") {
            redirecionar("minhas_reservas", "Essa reserva ja foi cancelada.", "erro");
        }

        if (!$this->model->cancelar($id)) {
            redirecionar("minhas_reservas", "Nao foi possivel cancelar a reserva.", "erro");
        }

        redirecionar(eAdmin() ? "admin_reservas" : "minhas_reservas", "Reserva cancelada. O quarto voltou a ficar disponivel.");
    }
}
