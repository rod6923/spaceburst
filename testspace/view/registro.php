<?php
require '../service/conexao.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $senha = password_hash($_POST['senha'], PASSWORD_BCRYPT);
    //Carregar imagem padrão
    $defaultImage = file_get_contents('img/pfp.jpg'); // Substitua pelo caminho correto


    try {
        $stmt = $pdo->prepare('INSERT INTO usuario (username, email, senha, fotoperfil) VALUES (:username, :email, :senha, :fotoperfil)');
        $stmt->execute([
            ':username' => $username,
            ':email' => $email,
            ':senha' => $senha,
            ':fotoperfil' => $defaultImage, 
        ]);

        $message = "Cadastro realizado com sucesso!";
    } catch (PDOException $e) {
        if ($e->getCode() == 23000) {
            $message = "Erro: Usuário ou e-mail já registrados.";
        } else {
            $message = "Erro ao registrar usuário: " . $e->getMessage();
        }
    }
    header('Location:login.php');
}

?>