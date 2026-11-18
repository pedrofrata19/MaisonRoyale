<?php

$dsn = "mysql:host=localhost;dbname=bd_maison_royale;charset=utf8mb4";

$opcoes = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
];

try {
    $pdo = new PDO($dsn, "root", "", $opcoes);
} catch (PDOException $erro) {
    die("Erro ao conectar ao banco de dados.");
}
