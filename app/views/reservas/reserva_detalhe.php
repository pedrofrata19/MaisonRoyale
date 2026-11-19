<?php
$titulo = "Detalhes da reserva";
require __DIR__ . "/../layouts/header.php";

$diarias = (new DateTime($reserva['check_in']))->diff(new DateTime($reserva['check_out']))->days;
?>

<section class="form-card">
    <h1>Detalhes da reserva #<?= (int) $reserva['id'] ?></h1>

    <dl class="detalhes-reserva">
        <dt>Quarto</dt>
        <dd><?= e($reserva['quarto']) ?> (<?= e($reserva['categoria']) ?>)</dd>

        <dt>Cliente</dt>
        <dd><?= e($reserva['cliente']) ?></dd>

        <dt>Check-in</dt>
        <dd><?= date('d/m/Y', strtotime($reserva['check_in'])) ?></dd>

        <dt>Check-out</dt>
        <dd><?= date('d/m/Y', strtotime($reserva['check_out'])) ?></dd>

        <dt>Diárias</dt>
        <dd><?= $diarias ?></dd>

        <dt>Hóspedes</dt>
        <dd><?= (int) $reserva['hospedes'] ?></dd>

        <dt>Valor da diária</dt>
        <dd>R$ <?= number_format((float) $reserva['preco'], 2, ',', '.') ?></dd>

        <dt>Valor total</dt>
        <dd><strong>R$ <?= number_format((float) $reserva['valor_total'], 2, ',', '.') ?></strong></dd>

        <dt>Status</dt>
        <dd>
            <span class="status <?= $reserva['status'] === 'Confirmada' ? 'confirmada' : 'cancelada' ?>">
                <?= e($reserva['status']) ?>
            </span>
        </dd>
    </dl>

    <?php if ($reserva['status'] === 'Confirmada'): ?>
        <form method="POST" action="index.php?acao=cancelar_reserva"
              onsubmit="return confirm('Deseja cancelar esta reserva?');">
            <input type="hidden" name="id" value="<?= (int) $reserva['id'] ?>">
            <button type="submit" class="btn-perigo">Cancelar reserva</button>
        </form>
    <?php endif; ?>

    <a href="<?= eAdmin() ? 'index.php?acao=admin_reservas' : 'index.php?acao=minhas_reservas' ?>">Voltar</a>
</section>

<?php require __DIR__ . "/../layouts/footer.php"; ?>
