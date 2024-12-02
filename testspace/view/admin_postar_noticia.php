<?php
include('../service/conexao.php');

// Função para gerar o slug
function gerarSlug($titulo) {
    $slug = strtolower($titulo);
    $slug = preg_replace('/\s+/', '-', $slug);
    $slug = preg_replace('/[^a-z0-9\-]/', '', $slug);
    return $slug;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['foto_noticia'])) {
    $titulo = $_POST['titulo'];
    $texto = $_POST['texto'];
    $foto = $_FILES['foto_noticia'];

    // Gerar o slug
    $slug = gerarSlug($titulo);

    // Verificar se o slug já existe no banco de dados
    $sql = "SELECT COUNT(*) FROM noticias WHERE slug = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$slug]);
    $count = $stmt->fetchColumn();

    if ($count > 0) {
        // Se o slug já existe, adicionar um sufixo para torná-lo único
        $slug .= '-' . time(); // Adiciona a timestamp para garantir unicidade
    }

    // Ler os dados binários da imagem
    $foto_dados = file_get_contents($foto['tmp_name']);

    // Inserir os dados no banco de dados
    $sql = "INSERT INTO noticias (titulo, texto, foto, slug) VALUES (?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$titulo, $texto, $foto_dados, $slug]);

    echo "Notícia postada com sucesso!";
}
?>
<style>
/* Estilo Geral */
body {
  margin: 0;
  font-family: 'Space Grotesk', sans-serif;
  background: #0d0d0d;
  color: white;
  display: flex;
  justify-content: center;
  align-items: center;
  height: 100vh;
  flex-direction: column;
}

/* Formulário de Criação de Notícia */
form {
  background: rgba(44, 47, 51, 0.8);
  padding: 20px;
  border-radius: 10px;
  box-shadow: 0 4px 10px rgba(0, 0, 0, 0.5);
  width: 100%;
  max-width: 500px;
  margin: 20px;
}

form label {
  display: block;
  font-size: 16px;
  color: #9b59b6;
  margin-bottom: 10px;
}

form input,
form textarea {
  width: 100%;
  padding: 10px;
  border: none;
  border-radius: 5px;
  background: rgba(255, 255, 255, 0.1);
  color: white;
  margin-bottom: 20px;
  font-size: 16px;
}

form input:focus,
form textarea:focus {
  outline: none;
  border: 1px solid #8a2be2;
}

form button {
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

form button:hover {
  background: #9b59b6;
  transform: scale(1.05);
}

/* Scroll personalizado */
textarea {
  resize: vertical;
  min-height: 80px;
  overflow-y: auto;
}

textarea::-webkit-scrollbar {
  width: 12px;
}

textarea::-webkit-scrollbar-track {
  background: #333;
  border-radius: 10px;
}

textarea::-webkit-scrollbar-thumb {
  background: #8a2be2;
  border-radius: 10px;
  border: 3px solid #333;
}

textarea::-webkit-scrollbar-thumb:hover {
  background: #9b59b6;
}

input[type="file"] {
  background: rgba(255, 255, 255, 0.1);
  color: white;
  border-radius: 5px;
  padding: 10px;
}

input[type="file"]:focus {
  outline: none;
  border: 1px solid #8a2be2;
}
/* Header */
.admin-header {
  background: rgba(44, 47, 51, 0.8);
  padding: 20px;
  border-radius: 10px;
  gap: 40px;
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
</style>
<div class="admin-header">
        <a href="home.php"><h1>Início</h1></a>
        <a href="admin_postar_noticia.php"><h1>Postar noticia</h1></a>
        <a href="editar_noticia.php"><h1>Editar</h1></a>
        <a href="logout.php"><h1>Sair</h1></a>
        
    </div>

<!-- Formulário para criar uma nova notícia -->
<form action="admin_postar_noticia.php" method="POST" enctype="multipart/form-data">
    <label for="titulo">Título da Notícia:</label>
    <input type="text" name="titulo" required><br>
    <label for="texto">Texto da Notícia:</label>
    <textarea name="texto" required></textarea><br>
    <label for="foto_noticia">Foto Principal:</label>
    <input type="file" name="foto_noticia" accept="image/*" required><br>
    <button type="submit">Postar Notícia</button>
</form>

</body>
