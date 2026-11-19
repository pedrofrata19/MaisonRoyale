<?php
$titulo = "Quartos";
$banner = true;
require __DIR__ . "/../layouts/header.php";

$checkIn = $_GET['check_in'] ?? '';
$checkOut = $_GET['check_out'] ?? '';
$hospedes = $_GET['hospedes'] ?? '';
$categoriaFiltro = $_GET['categoria'] ?? '';
?>

<section class="quartos">
    <h1>Nossas acomodações</h1>
    <p>Consulte a disponibilidade e escolha o quarto ideal para a sua estadia.</p>

    <form method="GET" action="index.php" class="filtro" id="busca">
        <input type="hidden" name="acao" value="quartos">

        <div>
            <label for="check_in">Check-in</label>
            <input type="date" id="check_in" name="check_in" value="<?= e($checkIn) ?>">
        </div>

        <div>
            <label for="check_out">Check-out</label>
            <input type="date" id="check_out" name="check_out" value="<?= e($checkOut) ?>" min="<?= e($checkIn) ?>">
        </div>

        <div>
            <label for="hospedes">Hóspedes</label>
            <input type="number" id="hospedes" name="hospedes" min="1" value="<?= e($hospedes) ?>" placeholder="1">
        </div>

        <div>
            <label for="categoria">Categoria</label>
            <select id="categoria" name="categoria">
                <option value="">Todas</option>
                <?php foreach ($categorias as $cat): ?>
                    <option value="<?= e($cat) ?>" <?= $categoriaFiltro === $cat ? 'selected' : '' ?>>
                        <?= e($cat) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <button type="submit">Buscar</button>
    </form>

    <?php if (!$quartos): ?>
        <p class="vazio">Nenhum quarto disponível para os filtros informados.</p>
    <?php else: ?>
        <div class="cards">
            <?php foreach ($quartos as $quarto): ?>
                <article class="card-quarto">
                    <?php if (!empty($quarto['imagem'])): ?>
                        <img src="<?= e(urlImagem($quarto['imagem'])) ?>" alt="<?= e($quarto['nome']) ?>">
                    <?php else: ?>
                        <div class="sem-imagem">Maison Royale</div>
                    <?php endif; ?>

                    <div class="card-corpo">
                        <span class="categoria"><?= e($quarto['categoria']) ?></span>
                        <h2><?= e($quarto['nome']) ?></h2>
                        <p><?= e($quarto['descricao']) ?></p>

                        <ul class="detalhes">
                            <li>Capacidade: <?= (int) $quarto['capacidade'] ?> hóspedes</li>
                            <li>Diária: <strong>R$ <?= number_format((float) $quarto['preco'], 2, ',', '.') ?></strong></li>
                        </ul>

                        <?php if (usuarioLogado()): ?>
                            <a class="btn" href="index.php?acao=reservar&id=<?= (int) $quarto['id'] ?>">Reservar</a>
                        <?php else: ?>
                            <a class="btn" href="index.php?acao=login">Faça login para reservar</a>
                        <?php endif; ?>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</section>

<?php require __DIR__ . "/../layouts/footer.php"; ?>
