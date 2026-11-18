<?php
$titulo = $quarto ? 'Editar quarto' : 'Cadastrar quarto';
require __DIR__ . "/../layouts/header.php";
require __DIR__ . "/../layouts/menu.php";

$edicao = (bool) $quarto;
?>

<section class="form-card">
    <h1><?= $edicao ? 'Editar quarto' : 'Cadastrar quarto' ?></h1>

    <form method="POST" action="index.php?acao=salvar_quarto" enctype="multipart/form-data">
        <?php if ($edicao): ?>
            <input type="hidden" name="id" value="<?= (int) $quarto['id'] ?>">
        <?php endif; ?>

        <label for="nome">Nome do quarto</label>
        <input type="text" id="nome" name="nome" minlength="3"
               value="<?= $edicao ? e($quarto['nome']) : old('nome') ?>" required>

        <label for="categoria">Categoria</label>
        <input type="text" id="categoria" name="categoria"
               value="<?= $edicao ? e($quarto['categoria']) : old('categoria') ?>" required>

        <label for="capacidade">Capacidade máxima de hóspedes</label>
        <input type="number" id="capacidade" name="capacidade" min="1"
               value="<?= $edicao ? e($quarto['capacidade']) : old('capacidade') ?>" required>

        <label for="preco">Valor da diária (R$)</label>
        <input type="number" id="preco" name="preco" min="0.01" step="0.01"
               value="<?= $edicao ? e(number_format((float) $quarto['preco'], 2, '.', '')) : old('preco') ?>" required>

        <label for="descricao">Descrição</label>
        <textarea id="descricao" name="descricao" rows="4"><?= $edicao ? e($quarto['descricao']) : old('descricao') ?></textarea>

        <input type="hidden" name="imagem_atual" value="<?= $edicao ? e($quarto['imagem']) : '' ?>">

        <label for="imagem">Imagem do quarto (opcional)</label>
        <input type="file" id="imagem" name="imagem" class="input-arquivo"
               accept="image/jpeg,image/png,image/webp,image/gif">
        <small class="ajuda">JPG, PNG, WEBP ou GIF · até 2 MB.</small>

        <?php if ($edicao && !empty($quarto['imagem'])): ?>
            <p class="ajuda">Imagem atual:</p>
            <img class="preview-imagem" src="<?= e(urlImagem($quarto['imagem'])) ?>"
                 alt="Imagem atual do quarto <?= e($quarto['nome']) ?>">
        <?php endif; ?>

        <button type="submit"><?= $edicao ? 'Atualizar quarto' : 'Cadastrar quarto' ?></button>
    </form>

    <a href="index.php?acao=admin_quartos">Voltar</a>
</section>

<?php require __DIR__ . "/../layouts/footer.php"; ?>
