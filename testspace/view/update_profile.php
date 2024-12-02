<?php
session_start();
include("../service/conexao.php");  // Conexão com o banco de dados

// Verifique se o usuário está logado
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Receber os dados do formulário
$new_username = $_POST['new_username'];
$new_photo = $_FILES['new_photo']['tmp_name'];

// Atualizar nome
$stmt = $pdo->prepare("UPDATE usuario SET username = :new_username WHERE id = :user_id");
$stmt->bindParam(':new_username', $new_username, PDO::PARAM_STR);
$stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$stmt->execute();

// Atualizar foto, se nova foto for enviada
if ($new_photo) {
    $imageData = file_get_contents($new_photo);
    $stmt = $pdo->prepare("UPDATE usuario SET fotoperfil = :fotoperfil WHERE id = :user_id");
    $stmt->bindParam(':fotoperfil', $imageData, PDO::PARAM_LOB);
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->execute();
}

header("Location: perfil.php");
exit();
?>
