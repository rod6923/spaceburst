

<?php
session_start();
require '../service/conexao.php';

// Verifique se o usuário está logado
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php'); // Redireciona para o login se não estiver logado
    exit();
}

$userId = $_SESSION['user_id'];

try {
    // Obter os dados do usuário
    $stmt = $pdo->prepare('SELECT * FROM usuario WHERE id = :id');
    $stmt->execute([':id' => $userId]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        echo "Usuário não encontrado.";
        exit();
    }
} catch (PDOException $e) {
    echo "Erro ao acessar os dados do usuário: " . $e->getMessage();
    exit();
}

// Obter o slug da URL
if (isset($_GET['slug'])) {
    $slug = $_GET['slug'];

    // Recuperar a notícia usando o slug
    $sql = "SELECT * FROM noticias WHERE slug = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$slug]);
    $noticia = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($noticia) {
      
        // Exibir a notícia
        echo "<div class='noticia-container'>";
        echo "<h1>" . htmlspecialchars($noticia['titulo']) . "</h1>";
              // Exibir a foto
              echo "<img src='data:image/jpeg;base64," . base64_encode($noticia['foto']) . "' alt='Imagem da Notícia'>";
        // echo "<span class='slug'>Slug: " . htmlspecialchars($noticia['slug']) . "</span>";
      
        echo "<p>" . nl2br(htmlspecialchars($noticia['texto'])) . "</p>";

        echo "</div>";
    } else {
        echo "Notícia não encontrada.";
    }
} else {
    echo "Slug não especificado.";
}
?>

<style>/* Estilização para o título e conteúdo da notícia */
.noticia-container {
    max-width: 900px;
    margin: 0 auto;
    padding: 20px;

    color: white; /* Cor do texto */
    border-radius: 15px;
    box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.5);
}

.noticia-container h1 {
    font-size: 36px;
    font-weight: bold;
    font-family: 'Sofia Sans Extra Condensed', sans-serif;
    text-align: center;
    margin-bottom: 20px;
    color: #f1f1f1;
}

.noticia-container p {
    font-family: 'Space Grotesk', sans-serif;
    font-size: 18px;
    line-height: 1.6;
    color: #ccc;
}

.noticia-container img {
    width: 100%;
    border-radius: 10px;
    margin-top: 20px;
}

.noticia-container .slug {
    display: block;
    font-size: 14px;
    font-weight: 600;
    font-family: 'Space Grotesk', sans-serif;
    color: #a8a8a8;
    margin-bottom: 10px;
    text-align: center;
}
</style>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>SpaceBurst</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css" />
  <script src="https://kit.fontawesome.com/2aa1d15dd4.js" crossorigin="anonymous"></script>
  <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Sofia+Sans+Extra+Condensed:ital,wght@0,1..1000;1,1..1000&display=swap" rel="stylesheet">
<style>
  @import url('https://fonts.googleapis.com/css2?family=Sofia+Sans+Extra+Condensed:ital,wght@0,1..1000;1,1..1000&family=Space+Grotesk:wght@300..700&display=swap');
  </style>
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
  <link rel="stylesheet" href="style/style.css">
</head>
<body>
  
  <audio id="background-music" loop>
    <source src="musicafundo.mp3" type="audio/mpeg">
    Seu navegador não suporta o elemento de áudio.
  </audio>
  
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      const audio = document.getElementById('background-music');
  
      // Define o volume inicial
      audio.volume = 0.05; // Volume de 20%
  
      // Tenta reproduzir o áudio diretamente
      audio.play().catch(error => {
        console.log("O navegador bloqueou o autoplay. Reproduza manualmente.");
      });
  
      // Garante que o áudio toque após interação
      document.addEventListener('click', () => {
        if (audio.paused) {
          audio.play();
        }
      }, { once: true }); // A interação é capturada apenas uma vez
    });
  </script>
  <!-- Foto de perfil com nome ao lado -->
<a href="perfil.php">
  <div class="profile-container">
    <div class="profile-picture">
      <?php
      // Exibir a foto de perfil, se existir
      if ($user['fotoperfil']) {
          // Mostrar a imagem armazenada no banco de dados
          echo '<img src="data:image/jpeg;base64,' . base64_encode($user['fotoperfil']) . '" alt="Foto de Perfil">';
      } else {
          // Caso o usuário não tenha foto, exibe uma imagem padrão
          echo '<img src="img/pfp.jpg" alt="Foto de Perfil">';
      }
      ?>
    </div>

    <!-- Nome do usuário -->
    <div class="profile-name">
      <span><?php echo htmlspecialchars($user['username']); ?></span>
    </div>
  </div>
</a>

    </div>


  
    <div class="video-container">
      <!-- Vídeo de fundo -->
      <video class="background-video" autoplay loop muted>
        <source src="vid/universo2.mp4" type="video/mp4">
        Seu navegador não suporta vídeos.
      </video>
    
      <!-- Capa preta transparente -->
      <div class="video-overlay"></div>
   <div class="fade"> </div>

    <!-- Capa preta transparente -->
   <div class="navbar">
    <a href="home.php"><i class="material-icons nav-button">home</i></a>
    <a href="#about"><i class="material-icons nav-button">info</i></a>
    <a href="#news"><i class="material-icons nav-button">newspaper</i></a>
    <a href="#contact"><i class="material-icons nav-button">mail</i></a>

    <?php
    // Verificar se o usuário é admin e mostrar o botão de admin

    if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {
        echo '<a href="admin.php" class="nav-button">
                <i class="material-icons">admin_panel_settings</i>
              </a>';
    }
    ?>

    <!-- Botão de Logout -->
    <a href="logout.php" class="nav-button">
        <i class="material-icons">exit_to_app</i>
    </a>
</div>
<script>
  // Seleciona todos os botões da navbar
  const buttons = document.querySelectorAll('.nav-button');

  // Cria o objeto de áudio para o som de hover
  const hoverSound = new Audio('mousehover.wav'); // Caminho correto para o arquivo de som

  // Adiciona o evento mouseover a cada botão
  buttons.forEach(button => {
    button.addEventListener('mouseover', () => {
      hoverSound.currentTime = 0; // Reinicia o som, caso já tenha tocado antes
      hoverSound.play().catch(error => {
        console.error("Erro ao tocar o som:", error); // Log de erro, caso o som não toque
      });
    });
  });
</script>
    </div></body></html>