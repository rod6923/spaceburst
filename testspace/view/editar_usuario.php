<?php
session_start();

// Verificar se o usuário está logado e se é um administrador
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header('Location: login.php');
    exit;
}

// Conexão com o banco de dados
require_once('../service/conexao.php');

// Obter o ID do usuário a ser editado
if (isset($_GET['id'])) {
    $usuario_id = $_GET['id'];

    // Buscar os dados do usuário
    $query = "SELECT id, nome, email, fotoperfil FROM usuarios WHERE id = :id";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':id', $usuario_id, PDO::PARAM_INT);
    $stmt->execute();
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    // Verificar se o usuário existe
    if (!$usuario) {
        echo "Usuário não encontrado!";
        exit;
    }
}

// Atualizar as informações do usuário
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $novo_nome = $_POST['nome'];
    $novo_email = $_POST['email'];
    $nova_foto = null;

    if (isset($_FILES['new_photo']) && $_FILES['new_photo']['error'] == 0) {
        $foto_temp = $_FILES['new_photo']['tmp_name'];
        $nova_foto = file_get_contents($foto_temp);
    }

    // Atualizar no banco de dados
    $update_query = "UPDATE usuarios SET nome = :nome, email = :email, fotoperfil = :fotoperfil WHERE id = :id";
    $stmt = $pdo->prepare($update_query);
    $stmt->bindParam(':nome', $novo_nome);
    $stmt->bindParam(':email', $novo_email);
    $stmt->bindParam(':fotoperfil', $nova_foto, PDO::PARAM_LOB);
    $stmt->bindParam(':id', $usuario_id);
    $stmt->execute();

    // Redirecionar para a página de admin
    header("Location: admin.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Usuário</title>
    <link rel="stylesheet" href="style/style.css">
</head>
<style>/* Reset básico */
body, h1, h2, p, form, input, button, label {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

/* Corpo da página */
body {
    font-family: Arial, sans-serif;
    background: #1c1c1c; /* Fundo escuro */
    color: #ffffff; /* Texto claro */
    line-height: 1.6;
}

/* Navbar */
.navbar {
    display: flex;
    justify-content: flex-start;
    align-items: center;
    background-color: #6a0dad; /* Roxo */
    padding: 10px 20px;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
    position: sticky;
    top: 0;
    z-index: 1000;
}

.navbar a {
    text-decoration: none;
    color: #ffffff;
    margin-right: 20px;
    padding: 10px 15px;
    border-radius: 5px;
    transition: background-color 0.3s ease;
}

.navbar a:hover {
    background-color: #4b0a8f; /* Escurecendo no hover */
}

/* Container */
.container {
    max-width: 800px;
    margin: 50px auto;
    padding: 20px;
    background: #2c2c2c; /* Fundo do formulário */
    border-radius: 10px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
}

.container h1 {
    text-align: center;
    color: #f0e68c; /* Amarelo claro */
    margin-bottom: 20px;
}

/* Formulário */
form {
    display: flex;
    flex-direction: column;
    gap: 15px;
}

form label {
    font-weight: bold;
    margin-bottom: 5px;
    color: #d1a0f0;
}

form input[type="text"],
form input[type="email"],
form input[type="file"] {
    padding: 10px;
    border: 1px solid #6a0dad;
    border-radius: 5px;
    background: #1e1e1e;
    color: #ffffff;
    font-size: 1rem;
}

form input[type="text"]:focus,
form input[type="email"]:focus,
form input[type="file"]:focus {
    outline: none;
    border-color: #9c27b0; /* Realce roxo */
}

form button {
    padding: 10px 20px;
    font-size: 1rem;
    color: #ffffff;
    background: #6a0dad;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

form button:hover {
    background-color: #4b0a8f;
}
</style>
<body>
    <!-- Navbar -->
    <div class="navbar">
        <a href="admin.php"><i class="material-icons nav-button">home</i> Administração</a>
        <a href="logout.php"><i class="material-icons nav-button">exit_to_app</i> Logout</a>
    </div>

    <div class="container">
        <h1>Editar Usuário</h1>
        <form action="editar_usuario.php?id=<?php echo $usuario['id']; ?>" method="POST" enctype="multipart/form-data">
            <div>
                <label for="nome">Nome:</label>
                <input type="text" name="nome" id="nome" value="<?php echo htmlspecialchars($usuario['nome']); ?>" required>
            </div>
            <div>
                <label for="email">Email:</label>
                <input type="email" name="email" id="email" value="<?php echo htmlspecialchars($usuario['email']); ?>" required>
            </div>
            <div>
                <label for="new_photo">Nova Foto de Perfil:</label>
                <input type="file" id="new_photo" name="new_photo" accept="image/*">
            </div>
            <button type="submit">Salvar Alterações</button>
        </form>
    </div>
</body>
</html>
