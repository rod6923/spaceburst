<?php
require '../service/conexao.php';

if (isset($_GET['id'])) {
    $userId = intval($_GET['id']);

    $stmt = $pdo->prepare('SELECT fotoperfil FROM usuario WHERE id = :id');
    $stmt->execute([':id' => $userId]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row && $row['fotoperfil']) {
        header('Content-Type: image/jpeg'); // Ou o tipo correto da imagem
        echo $row['fotoperfil'];
    } else {
        // Caso não tenha foto de perfil no banco, exibe uma imagem padrão
        echo file_get_contents('../img/pfp.jpg'); // Caminho da imagem padrão
    }
}
?>
    