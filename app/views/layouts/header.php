<?php
$titulo = $titulo ?? "Maison Royale";
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($titulo) ?> | Maison Royale</title>
    <link rel="stylesheet" href="/Maison_Royale/assets/css/style.css">
</head>
<body>
    <header class="topo">
        <div class="topo-conteudo">
            <a class="marca" href="index.php?acao=quartos">MAISON <span>ROYALE</span></a>
            <p class="slogan">Organização • Conforto • Excelência</p>
        </div>
    </header>

    <main class="container">
        <?= mensagem() ?>
