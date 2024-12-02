<?php
session_start();

// Verificar se o usuário está logado e tem permissões de admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header('Location: login.php');
    exit;
}

// Conexão com o banco de dados
require_once '../service/conexao.php';

// Busca todos os usuários no banco de dados
$query = "SELECT id, username, email, role, fotoperfil FROM usuario";
$stmt = $pdo->prepare($query);
$stmt->execute();
$usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Editar usuário
if (isset($_POST['editar_usuario'])) {
    $id_usuario = $_POST['id_usuario'];
    $novo_nome = $_POST['username'];
    $novo_email = $_POST['email'];
    $novo_role = $_POST['role'];
    $nova_foto = null;

    // Processar nova foto de perfil, se enviada
    if (isset($_FILES['new_photo']) && $_FILES['new_photo']['error'] == 0) {
        $foto_temp = $_FILES['new_photo']['tmp_name'];
        $nova_foto = file_get_contents($foto_temp);
    }

    // Atualiza as informações no banco de dados
    $query_update = "UPDATE usuario SET username = :username, email = :email, role = :role, fotoperfil = :fotoperfil WHERE id = :id";
    $stmt_update = $pdo->prepare($query_update);
    $stmt_update->bindParam(':username', $novo_nome);
    $stmt_update->bindParam(':email', $novo_email);
    $stmt_update->bindParam(':role', $novo_role);
    $stmt_update->bindParam(':fotoperfil', $nova_foto, PDO::PARAM_LOB);
    $stmt_update->bindParam(':id', $id_usuario);
    $stmt_update->execute();

    header('Location: admin.php');
    exit;
}

// Excluir usuário
if (isset($_POST['excluir_usuario'])) {
    $id_usuario = $_POST['id_usuario'];

    $query_delete = "DELETE FROM usuario WHERE id = :id";
    $stmt_delete = $pdo->prepare($query_delete);
    $stmt_delete->bindParam(':id', $id_usuario);
    $stmt_delete->execute();

    header('Location: admin.php');
    exit;
}
?>
<?php

try {
    // Criar uma instância PDO
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Define o modo de erro

    // Consulta para contar o número de usuários
    $sql = "SELECT COUNT(*) AS total_usuarios FROM usuario";
    $stmt = $pdo->query($sql); // Executa a consulta

    // Recupera o resultado
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    $totalUsuarios = $row['total_usuarios'];
} catch (PDOException $e) {
    echo "Erro na conexão: " . $e->getMessage(); // Caso haja erro na conexão
}

// Verificar se o parâmetro de exclusão foi passado
if (isset($_GET['excluir'])) {
    $id_noticia = $_GET['excluir'];

    // Excluir a notícia do banco de dados
    $sql = "DELETE FROM noticias WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $id_noticia);
    $stmt->execute();

    // Redirecionar de volta para a página admin
    header('Location: admin.php');
    exit;
}
// Excluir notícia
if (isset($_GET['excluir'])) {
    $id_noticia = $_GET['excluir'];

    // Excluir a notícia do banco de dados
    $sql = "DELETE FROM noticias WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $id_noticia);
    $stmt->execute();

    // Redirecionar de volta para a página admin
    header('Location: admin.php');
    exit;
}
// Editar notícia
// Editar notícia
if (isset($_POST['editar_noticia'])) {
    $id_noticia = $_POST['id_noticia'];
    $novo_titulo = $_POST['titulo'];
    $nova_legenda = $_POST['legenda'];
    $nova_imagem = null;

    // Processar nova imagem, se enviada
    if (isset($_FILES['new_image']) && $_FILES['new_image']['error'] == 0) {
        $imagem_temp = $_FILES['new_image']['tmp_name'];
        $nova_imagem = file_get_contents($imagem_temp);
    }

    // Se não houver nova imagem, a variável $nova_imagem será null
    // Se a imagem não for enviada, devemos manter a imagem existente no banco de dados

    if ($nova_imagem === null) {
        // Se não houver nova imagem, atualiza sem alterar o campo 'foto'
        $query_update = "UPDATE noticias SET titulo = :titulo, texto = :texto WHERE id = :id";
        $stmt_update = $pdo->prepare($query_update);
    } else {
        // Se houver nova imagem, atualiza também o campo 'foto'
        $query_update = "UPDATE noticias SET titulo = :titulo, texto = :texto, foto = :foto WHERE id = :id";
        $stmt_update = $pdo->prepare($query_update);
        $stmt_update->bindParam(':foto', $nova_imagem, PDO::PARAM_LOB);
    }

    // Bind dos parâmetros para título e legenda
    $stmt_update->bindParam(':titulo', $novo_titulo);
    $stmt_update->bindParam(':texto', $nova_legenda);
    $stmt_update->bindParam(':id', $id_noticia);

    // Executa a query
    $stmt_update->execute();

    // Redireciona para a página de administração
    header('Location: admin.php');
    exit;
}

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel de Administração</title>
    <link rel="stylesheet" href="style.css">
</head>
<style>/* Tabela */
/* Estilo Geral */
body {
  margin: 0;
  font-family: 'Space Grotesk', sans-serif;
  background: #0d0d0d;
  color: white;
}

/* Container Principal */
.admin-container {
  display: flex;
  flex-direction: column;
  min-height: 100vh;
  padding: 20px;
}

/* Header */
.admin-header {
  background: rgba(44, 47, 51, 0.8);
  padding: 20px;
  border-radius: 10px;
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.admin-header h1 {
  font-family: 'Sofia Sans Extra Condensed', sans-serif;
  font-size: 32px;
  color: #9b59b6;
}

.admin-header button {
  background: #3f3f3f;
  color: white;
  padding: 10px 20px;
  border: none;
  border-radius: 5px;
  font-size: 16px;
  cursor: pointer;
  transition: background 0.3s ease, transform 0.2s ease;
}

.admin-header button:hover {
  background: #8a2be2;
  transform: scale(1.05);
}

/* Tabela */
.admin-table {
  width: 100%;
  border-collapse: collapse;
  margin-top: 20px;
  background: rgba(44, 47, 51, 0.6);
  border-radius: 10px;
  overflow: hidden;
}

.admin-table th,
.admin-table td {
  padding: 15px;
  text-align: left;
  border-bottom: 1px solid rgba(255, 255, 255, 0.1);
}

.admin-table th {
  background: rgba(44, 47, 51, 0.8);
  font-family: 'Sofia Sans Extra Condensed', sans-serif;
  font-size: 18px;
  color: #9b59b6;
}

.admin-table td {
  font-size: 16px;
  color: white;
}

.admin-table tr:hover {
  background: rgba(255, 255, 255, 0.1);
}

/* Botões de Ação */
.action-buttons {
  display: flex;
  gap: 10px;
}

.action-buttons button {
  padding: 5px 10px;
  font-size: 14px;
  border: none;
  border-radius: 5px;
  cursor: pointer;
  transition: background 0.3s ease, transform 0.2s ease;
}

.action-buttons .edit-btn {
  background: #3f3f3f;
  color: white;
}

.action-buttons .edit-btn:hover {
  background: #6a0dad;
  transform: scale(1.05);
}

.action-buttons .delete-btn {
  background: #8a2be2;
  color: white;
}

.action-buttons .delete-btn:hover {
  background: #9b59b6;
  transform: scale(1.05);
}

/* Formulário */
.admin-form {
  margin-top: 20px;
  padding: 20px;
  background: rgba(44, 47, 51, 0.6);
  border-radius: 10px;
}

.admin-form label {
  display: block;
  font-size: 16px;
  margin-bottom: 10px;
  color: #9b59b6;
}

.admin-form input,
.admin-form select {
  width: 100%;
  padding: 10px;
  border: none;
  border-radius: 5px;
  background: rgba(255, 255, 255, 0.1);
  color: white;
  margin-bottom: 20px;
  font-size: 16px;
}

.admin-form input:focus,
.admin-form select:focus {
  outline: none;
  border: 1px solid #8a2be2;
}

.admin-form button {
  width: 100%;
  padding: 10px;
  font-size: 16px;
  border: none;
  border-radius: 5px;
  background: #8a2be2;
  color: white;
  cursor: pointer;
  transition: background 0.3s ease, transform 0.2s ease;
}

.admin-form button:hover {
  background: #9b59b6;
  transform: scale(1.05);
}

/* Rodapé */
.admin-footer {
  margin-top: auto;
  text-align: center;
  padding: 20px;
  background: rgba(44, 47, 51, 0.8);
  border-radius: 10px;
  color: #9b59b6;
}
.profile-picture {
  width: 80px;
  height: 80px;
  border-radius: 50%;
  overflow: hidden;
  margin-right: 10px; /* Espaço entre a foto e o nome */
}

.profile-picture img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}
/* Contagem de Usuários */
.user-count {
    background: rgba(44, 47, 51, 0.8); /* Fundo translúcido */
    color: white;
    padding: 10px 20px;
    border-radius: 10px;
    margin-bottom: 20px;
    font-size: 18px;
    font-family: 'Space Grotesk', sans-serif;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.5);
}

.user-count strong {
    color: #9b59b6; /* Cor do texto forte */
}
/* Botões de Ação */
.action-buttons {
  display: flex;
  gap: 10px;
}

.action-buttons button {
  padding: 5px 10px;
  font-size: 14px;
  border: none;
  border-radius: 5px;
  cursor: pointer;
  transition: background 0.3s ease, transform 0.2s ease;
}

.action-buttons .edit-btn {
  background: #3f3f3f;
  color: white;
}

.action-buttons .edit-btn:hover {
  background: #6a0dad;
  transform: scale(1.05);
}

.action-buttons .delete-btn {
  background: #8a2be2;
  color: white;
}

.action-buttons .delete-btn:hover {
  background: #9b59b6;
  transform: scale(1.05);
}
.admin-input {
    width: 100%;
    padding: 10px;
    border: none;
    border-radius: 5px;
    background: rgba(255, 255, 255, 0.1);
    color: white;
    font-size: 16px;
    margin-bottom: 20px;
}

textarea.admin-input {
    resize: vertical; /* Permite redimensionar o campo apenas na vertical */
    min-height: 80px; /* Define uma altura mínima para o campo */
}
/* Aplicando scroll personalizado em um textarea específico */
textarea.admin-input {
    resize: vertical;
    min-height: 80px;
    overflow-y: auto; /* Permite rolagem vertical */
}

/* Para aplicar a personalização do scroll */
textarea.admin-input::-webkit-scrollbar {
    width: 12px;
}

textarea.admin-input::-webkit-scrollbar-track {
    background: #333;
    border-radius: 10px;
}

textarea.admin-input::-webkit-scrollbar-thumb {
    background: #8a2be2;
    border-radius: 10px;
    border: 3px solid #333;
}

textarea.admin-input::-webkit-scrollbar-thumb:hover {
    background: #9b59b6;
}
/* Aplicando scroll personalizado em um textarea específico */
textarea.admin-input {
    resize: vertical;
    min-height: 80px;
    overflow-y: auto; /* Permite rolagem vertical */
}

/* Para aplicar a personalização do scroll */
textarea.admin-input::-webkit-scrollbar {
    width: 12px;
}

textarea.admin-input::-webkit-scrollbar-track {
    background: #333;
    border-radius: 10px;
}

textarea.admin-input::-webkit-scrollbar-thumb {
    background: #8a2be2;
    border-radius: 10px;
    border: 3px solid #333;
}

textarea.admin-input::-webkit-scrollbar-thumb:hover {
    background: #9b59b6;
}

</style>
<body>
    <!-- Navbar -->
    <div class="admin-header">
        <a href="home.php"><h1>Início</h1></a>
        <a href="admin_postar_noticia.php"><h1>Postar noticia</h1></a>
        <a href="editar_noticia.php"><h1>Editar</h1></a>
        <a href="logout.php"><h1>Sair</h1></a>
        
    </div>

    <!-- Container Principal -->
    <div class="admin-container">
        <!-- Header -->
        <div class="admin-header">
            <h1>Gestão de Usuários</h1>
            <!-- Exibir a contagem de usuários -->
            <div class="user-count">
                <p><strong>Total de Usuários:</strong> <?php echo $totalUsuarios; ?></p>
            </div>
        </div>
<!-- Tabela de Notícias -->
<table class="admin-table">
    <thead>
        <tr>
            <th>Título</th>
            <th>Legenda</th>
            <th>Imagem</th>
            <th>Ações</th>
        </tr>
    </thead>
    <tbody>
        <?php
        // Buscar notícias no banco de dados
        $query = "SELECT * FROM noticias";
        $stmt = $pdo->prepare($query);
        $stmt->execute();
        $noticias = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($noticias as $noticia):
        ?>
        <tr>
            <form action="admin.php" method="POST" enctype="multipart/form-data">
                <td>
                    <input 
                        type="text" 
                        class="admin-input" 
                        name="titulo" 
                        value="<?php echo htmlspecialchars($noticia['titulo']); ?>" 
                        required
                    >
                </td>
                <td>
    <textarea 
        class="admin-input" 
        name="legenda" 
        required
        rows="4" 
        style="resize: vertical;"
    ><?php echo htmlspecialchars($noticia['texto']); ?></textarea>
</td>
                <td>
                    <div class="image-preview">
                        <img 
                            src="data:image/jpeg;base64,<?php echo base64_encode($noticia['foto']); ?>" 
                            alt="Imagem da Notícia"
                            style="width: 100px; height: 100px; object-fit: cover;"
                        >
                    </div>
                    <input type="file" name="new_image" accept="image/*" class="admin-input-file">
                </td>
                <td class="action-buttons">
                    <!-- Editar -->
                    <input type="hidden" name="id_noticia" value="<?php echo $noticia['id']; ?>">
                    <button 
                        type="submit" 
                        name="editar_noticia" 
                        class="edit-btn btn-hover"
                    >
                        Editar
                    </button>
                    <!-- Excluir -->
                    <a href="admin.php?excluir=<?php echo $noticia['id']; ?>" 
                        onclick="return confirm('Tem certeza que deseja excluir esta notícia?')">
                        <button type="button" class="delete-btn btn-hover">
                            Excluir
                        </button>
                    </a>
                </td>
            </form>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

        <!-- Tabela de Usuários -->
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Nome</th>
                    <th>Email</th>
                    <th>Foto de Perfil</th>
                    <th>Papel</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($usuarios as $usuario): ?>
                    <tr>
                        <form action="admin.php" method="POST" enctype="multipart/form-data">
                            <td>
                                <input 
                                    type="text" 
                                    class="admin-input" 
                                    name="username" 
                                    value="<?php echo htmlspecialchars($usuario['username']); ?>" 
                                    required
                                >
                            </td>
                            <td>
                                <input 
                                    type="email" 
                                    class="admin-input" 
                                    name="email" 
                                    value="<?php echo htmlspecialchars($usuario['email']); ?>" 
                                    required
                                >
                            </td>
                            <td>
                                <div class="profile-picture">
                                    <img 
                                        src="data:image/jpeg;base64,<?php echo base64_encode($usuario['fotoperfil']); ?>" 
                                        alt="Foto de Perfil"
                                    >
                                </div>
                                <input type="file" name="new_photo" accept="image/*" class="admin-input-file">
                            </td>
                            <td>
                                <select name="role" class="admin-input">
                                    <option value="admin" <?php echo $usuario['role'] === 'admin' ? 'selected' : ''; ?>>
                                        Administrador
                                    </option>
                                    <option value="usuario" <?php echo $usuario['role'] === 'usuario' ? 'selected' : ''; ?>>
                                        Usuário
                                    </option>
                                </select>
                            </td>
                            <td class="action-buttons">
                                <!-- Editar -->
                                <input type="hidden" name="id_usuario" value="<?php echo $usuario['id']; ?>">
                                <button 
                                    type="submit" 
                                    name="editar_usuario" 
                                    class="edit-btn btn-hover"
                                >
                                    Salvar
                                </button>
                                <!-- Excluir -->
                                <button 
                                    type="submit" 
                                    name="excluir_usuario" 
                                    class="delete-btn btn-hover" 
                                    onclick="return confirm('Tem certeza que deseja excluir este usuário?')"
                                >
                                    Excluir
                                </button>
                            </td>
                        </form>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <!-- Rodapé -->
        <div class="admin-footer">
            © 2024 Gestão de Usuários
        </div>
    </div>
</body>
