<?php
$titulo = "Login";
require __DIR__ . "/../layouts/header.php";
require __DIR__ . "/../layouts/menu.php";
?>

<section class="auth-card">
    <h1>Acesse sua conta</h1>
    <p>Entre para consultar acomodações e realizar reservas.</p>

    <form method="POST" action="index.php?acao=entrar">
        <label for="email">E-mail</label>
        <input type="email" id="email" name="email" value="<?= old('email') ?>" required>

        <label for="senha">Senha</label>
        <input type="password" id="senha" name="senha" required>

        <button type="submit">Entrar</button>
    </form>

    <p class="auth-link">
        Ainda não tem conta?
        <a href="index.php?acao=cadastro_usuario">Criar conta</a>
    </p>
</section>

<?php require __DIR__ . "/../layouts/footer.php"; ?>
