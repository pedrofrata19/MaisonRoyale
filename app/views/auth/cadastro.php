<?php
$titulo = "Criar conta";
require __DIR__ . "/../layouts/header.php";
?>

<section class="auth-card">
    <h1>Criar conta</h1>
    <p>Cadastre-se para reservar os quartos do Maison Royale.</p>

    <form method="POST" action="index.php?acao=salvar_usuario">
        <label for="nome">Nome completo</label>
        <input type="text" id="nome" name="nome" value="<?= old('nome') ?>" minlength="3" required>

        <label for="email">E-mail</label>
        <input type="email" id="email" name="email" value="<?= old('email') ?>" required>

        <label for="cpf">CPF</label>
        <input type="text" id="cpf" name="cpf" value="<?= old('cpf') ?>" placeholder="000.000.000-00" maxlength="14" required>

        <label for="senha">Senha</label>
        <input type="password" id="senha" name="senha" minlength="6" required>

        <label for="confirmar_senha">Confirmar senha</label>
        <input type="password" id="confirmar_senha" name="confirmar_senha" minlength="6" required>

        <button type="submit">Cadastrar</button>
    </form>

    <p class="auth-link">
        Já possui conta? <a href="index.php?acao=login">Voltar para o login</a>
    </p>
</section>

<?php require __DIR__ . "/../layouts/footer.php"; ?>
