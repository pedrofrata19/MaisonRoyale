<?php
$titulo = "Administrar quartos";
require __DIR__ . "/../layouts/header.php";
?>

<section>
    <div class="cabecalho-secao">
        <h1>Administrar quartos</h1>
        <a class="btn" href="index.php?acao=cadastrar_quarto">Novo quarto</a>
    </div>

    <div class="tabela-responsive">
        <table>
            <thead>
                <tr>
                    <th>Quarto</th>
                    <th>Categoria</th>
                    <th>Capacidade</th>
                    <th>Diária</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($quartos as $quarto): ?>
                    <tr>
                        <td><?= e($quarto['nome']) ?></td>
                        <td><?= e($quarto['categoria']) ?></td>
                        <td><?= (int) $quarto['capacidade'] ?></td>
                        <td>R$ <?= number_format((float) $quarto['preco'], 2, ',', '.') ?></td>
                        <td class="acoes-tabela">
                            <a href="index.php?acao=editar_quarto&id=<?= (int) $quarto['id'] ?>">Editar</a>
                            <form method="POST" action="index.php?acao=excluir_quarto" class="form-inline"
                                  onsubmit="return confirm('Deseja excluir este quarto?');">
                                <input type="hidden" name="id" value="<?= (int) $quarto['id'] ?>">
                                <button type="submit" class="btn-perigo">Excluir</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</section>

<?php require __DIR__ . "/../layouts/footer.php"; ?>
