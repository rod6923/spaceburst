<?php
session_start();  // Inicia a sessão

require '../service/conexao.php'; // Inclua a conexão com o banco de dados

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    // Verificar o usuário e a senha no banco de dados
    $stmt = $pdo->prepare('SELECT id, senha, role FROM usuario WHERE email = :email');
    $stmt->execute([':email' => $email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($senha, $user['senha'])) {
        // Se a senha for válida, armazene os dados do usuário na sessão
        $_SESSION['user_id'] = $user['id'];       // Armazena o ID do usuário
        $_SESSION['role'] = $user['role'];       // Armazena o papel do usuário (ex: 'admin' ou 'user')
        $_SESSION['email'] = $email;          // Opcional: Armazena o e-mail ou nome de usuário para referência
        
        header('Location: home.php');  // Redireciona para a página inicial
        exit();
    } else {
        $message = "Email ou senha incorretos!";
    }
}
?>
