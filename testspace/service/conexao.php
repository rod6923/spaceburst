<?php
try {
    $dsn = 'mysql:host=localhost;dbname=spaceburst;charset=utf8mb4';
    $username = 'root'; // Substitua pelo seu usuário do MySQL
    $password = ''; // Substitua pela sua senha do MySQL
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ];

    $pdo = new PDO($dsn, $username, $password, $options);
} catch (PDOException $e) {
    die("Erro ao conectar ao banco de dados: " . $e->getMessage());
}
