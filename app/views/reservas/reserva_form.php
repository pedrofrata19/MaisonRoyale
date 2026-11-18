<?php
$titulo = "Reservar quarto";
require __DIR__ . "/../layouts/header.php";
require __DIR__ . "/../layouts/menu.php";

$preco = (float) $quarto['preco'];
?>

<section class="form-card">
    <h1>Reservar <?= e($quarto['nome']) ?></h1>
    <p>
        <?= e($quarto['categoria']) ?> · Capacidade máxima de
        <?= (int) $quarto['capacidade'] ?> hóspedes ·
        Diária de <strong>R$ <?= number_format($preco, 2, ',', '.') ?></strong>
    </p>

    <form method="POST" action="index.php?acao=salvar_reserva" id="form-reserva" data-preco="<?= e($preco) ?>">
        <input type="hidden" name="quarto_id" value="<?= (int) $quarto['id'] ?>">

        <label for="check_in">Check-in</label>
        <input type="date" id="check_in" name="check_in" value="<?= old('check_in') ?>" min="<?= date('Y-m-d') ?>" required>

        <label for="check_out">Check-out</label>
        <input type="date" id="check_out" name="check_out" value="<?= old('check_out') ?>" min="<?= date('Y-m-d', strtotime('+1 day')) ?>" required>

        <label for="hospedes">Quantidade de hóspedes</label>
        <input type="number" id="hospedes" name="hospedes" min="1" max="<?= (int) $quarto['capacidade'] ?>" value="<?= old('hospedes', '1') ?>" required>

        <p class="total">Valor total: <strong id="total-reserva">R$ 0,00</strong></p>
        <p class="aviso">O valor é calculado pela quantidade de diárias.</p>

        <button type="submit">Confirmar reserva</button>
    </form>

    <a href="index.php?acao=quartos">Voltar aos quartos</a>
</section>

<?php require __DIR__ . "/../layouts/footer.php"; ?>
