
<?php
$titulo = $titulo ?? "Maison Royale";
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="assets/img/logo-maisonroyale.png" type="image/x-icon">
    <title><?= e($titulo) ?> | Maison Royale</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body>

<header class="topo">
    <div class="topo-conteudo">

        <a class="marca" href="index.php?acao=quartos">
            <img class="logo" src="assets/img/logo-maisonroyale.png" alt="Logo do MaisonRoyale">
        </a>

        <nav class="menu">
            <div class="menu-centro">

                <a href="index.php?acao=quartos">Quartos</a>

                <?php if (usuarioLogado()): ?>

                    <a href="index.php?acao=painel">Painel</a>
                    <a href="index.php?acao=minhas_reservas">Minhas reservas</a>

                    <?php if (eAdmin()): ?>
                        <a href="index.php?acao=admin_quartos">Quartos</a>
                        <a href="index.php?acao=admin_reservas">Reservas</a>
                        <a href="index.php?acao=admin_usuarios">Usuários</a>
                    <?php endif; ?>

                <?php else: ?>

                    <a href="index.php?acao=login">Login</a>
                    <a href="index.php?acao=cadastro_usuario">Criar conta</a>

                <?php endif; ?>

            </div>

            <?php if (usuarioLogado()): ?>
                <div class="menu-direita">
                    <span class="usuario-logado">
                        Olá, <?= e($_SESSION["nome"]) ?>
                    </span>

                    <a href="index.php?acao=sair" class="sair">Sair</a>
                </div>
            <?php endif; ?>
        </nav>

    </div>
</header>

<?php if (!empty($banner)): ?>
    <section class="banner-busca" aria-label="Banner Maison Royale">
        <img src="assets/img/banner.png" alt="Acomodação elegante do Maison Royale">

        <div class="banner-conteudo">
            <h1>Encontre seu quarto ideal</h1>
            <p>Organização • Conforto • Excelência</p>
            <a class="btn" href="#busca">Buscar disponibilidade</a>
        </div>
    </section>
<?php endif; ?>

<main class="container">

    <?= mensagem() ?>
