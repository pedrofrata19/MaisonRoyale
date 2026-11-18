<nav class="menu">
    <a href="index.php?acao=quartos">Quartos</a>

    <?php if (usuarioLogado()): ?>
        <a href="index.php?acao=painel">Painel</a>
        <a href="index.php?acao=minhas_reservas">Minhas reservas</a>

        <?php if (eAdmin()): ?>
            <a href="index.php?acao=admin_quartos">Admin · Quartos</a>
            <a href="index.php?acao=admin_reservas">Admin · Reservas</a>
            <a href="index.php?acao=admin_usuarios">Admin · Usuários</a>
        <?php endif; ?>

        <span class="usuario-logado">Olá, <?= e($_SESSION["nome"]) ?></span>
        <a href="index.php?acao=sair" class="sair">Sair</a>
    <?php else: ?>
        <a href="index.php?acao=login">Login</a>
        <a href="index.php?acao=cadastro_usuario">Criar conta</a>
    <?php endif; ?>
</nav>
