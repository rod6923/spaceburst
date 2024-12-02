<?php
session_start();
require '../service/conexao.php';

// Verifique se o usuário está logado
if (!isset($_SESSION['id'])) {
    header('Location: index.php');
    exit();
}

$userId = $_SESSION['id'];
$username = $_POST['username'];

// Verificar se uma nova foto foi enviada
$sql = 'UPDATE usuario SET username = :username';

$params = [':id' => $userId, ':username' => $username];

if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['size'] > 0) {
    $profilePicture = file_get_contents($_FILES['profile_picture']['tmp_name']);
    $sql .= ', fotoperfil = :fotoperfil';
    $params[':fotoperfil'] = $profilePicture;
}

$sql .= ' WHERE id = :id';

$stmt = $pdo->prepare($sql);

if ($stmt->execute($params)) {
    header('Location: profile.php?success=1');
} else {
    echo 'Erro ao atualizar perfil.';
}
