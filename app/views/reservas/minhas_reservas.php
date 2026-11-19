<?php
$titulo = "Minhas reservas";
require __DIR__ . "/../layouts/header.php";
?>

<section>
    <h1>Minhas reservas</h1>

    <?php if (!$reservas): ?>
        <p class="vazio">Você ainda não possui reservas.</p>
        <a class="btn" href="index.php?acao=quartos">Consultar quartos</a>
    <?php else: ?>
        <div class="tabela-responsive">
            <table>
                <thead>
                    <tr>
                        <th>Quarto</th>
                        <th>Check-in</th>
                        <th>Check-out</th>
                        <th>Hóspedes</th>
                        <th>Valor total</th>
                        <th>Status</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($reservas as $reserva): ?>
                        <tr>
                            <td><?= e($reserva['quarto']) ?></td>
                            <td><?= date('d/m/Y', strtotime($reserva['check_in'])) ?></td>
                            <td><?= date('d/m/Y', strtotime($reserva['check_out'])) ?></td>
                            <td><?= (int) $reserva['hospedes'] ?></td>
                            <td>R$ <?= number_format((float) $reserva['valor_total'], 2, ',', '.') ?></td>
                            <td>
                                <span class="status <?= $reserva['status'] === 'Confirmada' ? 'confirmada' : 'cancelada' ?>">
                                    <?= e($reserva['status']) ?>
                                </span>
                            </td>
                            <td class="acoes-tabela">
                                <a href="index.php?acao=reserva_detalhe&id=<?= (int) $reserva['id'] ?>">Detalhes</a>

                                <?php if ($reserva['status'] === 'Confirmada'): ?>
                                    <form method="POST" action="index.php?acao=cancelar_reserva" class="form-inline"
                                          onsubmit="return confirm('Deseja cancelar esta reserva?');">
                                        <input type="hidden" name="id" value="<?= (int) $reserva['id'] ?>">
                                        <button type="submit" class="btn-perigo">Cancelar</button>
                                    </form>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</section>

<?php require __DIR__ . "/../layouts/footer.php"; ?>
