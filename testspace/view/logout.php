<?php
// Iniciar a sessão
session_start();

// Destruir a sessão e redirecionar para a página inicial ou login
session_unset();
session_destroy();

// Redirecionar o usuário para a página de login ou home
header("Location: index.php"); // Ou qualquer página de sua escolha
exit;
?>
