<?php
$titulo = "Painel";
require __DIR__ . "/layouts/header.php";
require __DIR__ . "/layouts/menu.php";
?>

<section class="painel">
    <h1>Painel de Controle</h1>
    <p>Bem-vindo ao Maison Royale, <?= e($_SESSION['nome']) ?>.</p>

    <div class="acoes">
        <a class="btn" href="index.php?acao=quartos">Consultar quartos</a>

        <?php if (eAdmin()): ?>
            <a class="btn" href="index.php?acao=admin_quartos">Gerenciar quartos</a>
            <a class="btn" href="index.php?acao=admin_reservas">Gerenciar reservas</a>
            <a class="btn" href="index.php?acao=admin_usuarios">Consultar usuários</a>
        <?php else: ?>
            <a class="btn" href="index.php?acao=minhas_reservas">Minhas reservas</a>
        <?php endif; ?>
    </div>
</section>

<?php require __DIR__ . "/layouts/footer.php"; ?>
