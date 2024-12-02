<?php
// Incluir a conexão com o banco de dados
include('../service/conexao.php');

// Verificar se foi passado o slug da notícia
if (isset($_GET['slug'])) {
    $slug = $_GET['slug'];

    // Recuperar a notícia pelo slug
    $sql = "SELECT * FROM noticias WHERE slug = :slug";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':slug', $slug);
    $stmt->execute();
    $noticia = $stmt->fetch(PDO::FETCH_ASSOC);

    // Se não encontrar a notícia, redireciona para o painel
    if (!$noticia) {
        header('Location: admin.php');
        exit;
    }

    // Processar a edição do formulário
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Recuperar os dados do formulário
        $titulo = $_POST['titulo'];
        $texto = $_POST['texto'];
        $foto = $_FILES['foto']['tmp_name'];

        // Se uma nova foto for enviada, processa a imagem
        if ($foto) {
            $foto_conteudo = file_get_contents($foto);
        } else {
            $foto_conteudo = $noticia['foto']; // Se não alterar a foto, mantém a atual
        }

        // Atualizar a notícia no banco
        $sql = "UPDATE noticias SET titulo = :titulo, texto = :texto, foto = :foto WHERE slug = :slug";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':titulo', $titulo);
        $stmt->bindParam(':texto', $texto);
        $stmt->bindParam(':foto', $foto_conteudo, PDO::PARAM_LOB);
        $stmt->bindParam(':slug', $slug);
        $stmt->execute();

        // Redirecionar de volta para a página admin
        header('Location: admin.php');
        exit;
    }
} else {
    header('Location: admin.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Notícia</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="navbar">
        <a href="home.php">Início</a>
        <a href="logout.php">Sair</a>
    </div>

    <div class="container">
        <h1>Editar Notícia: <?php echo htmlspecialchars($noticia['titulo']); ?></h1>
        <form action="editar_noticia.php?slug=<?php echo $noticia['slug']; ?>" method="POST" enctype="multipart/form-data">
            <div>
                <label for="titulo">Título</label>
                <input type="text" id="titulo" name="titulo" value="<?php echo htmlspecialchars($noticia['titulo']); ?>" required>
            </div>

            <div>
                <label for="texto">Texto</label>
                <textarea id="texto" name="texto" rows="6" required><?php echo htmlspecialchars($noticia['texto']); ?></textarea>
            </div>

            <div>
                <label for="foto">Imagem</label>
                <input type="file" id="foto" name="foto" accept="image/*">
            </div>

            <button type="submit">Salvar Alterações</button>
        </form>
    </div>
</body>
</html>
