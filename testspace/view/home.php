<!DOCTYPE html>
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
?>

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
  
  <div id="popup" class="popup-overlay">
    <div class="popup-content">
      <h2>Bem-vindo ao SpaceBurst!</h2>
      <p>O SpaceBurst é um portal dedicado a notícias e conteúdos fascinantes sobre astronomia e o universo. Clique no botão abaixo para começar sua jornada!</p>
      <button id="enter-site">Ok, obrigado</button>
    </div>
  </div>
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      const popup = document.getElementById('popup');
      const enterButton = document.getElementById('enter-site');
  
      // Esconde o popup quando o botão for clicado
      enterButton.addEventListener('click', () => {
        popup.style.display = 'none';
      });
    });
  </script>
  
  
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
    <a href="#home"><i class="material-icons nav-button">home</i></a>
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
 <?php
    // Verificar se o usuário é admin e mostrar o botão de admin

    if (isset($_SESSION['role']) && $_SESSION['role'] === 'jornalista') {
        echo '<a href="postarnoticia.php" class="nav-button">
                <i class="material-icons">newspaper</i>
              </a>';
    }
    ?>
    <!-- Botão de Logout -->
    <a href="logout.php" class="nav-button">
        <i class="material-icons">exit_to_app</i>
    </a>
</div>


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
  <!-- Boas-vindas -->
  <div class="content">
    <h1 class="title">SPACEBURST</h1>
    <p class="subtitle">Explorando as maravilhas do universo com você.</p>
    <a href="login.php" class="btn nav-button" >Entrar ou Criar Conta</a>
  </div>

  <!-- Seções -->
  <div class="sections">
   <!-- Vídeos -->
<div class="section">
  <p class="explic">Explore o universo conosco! Aqui, você encontrará as últimas notícias sobre astronomia, descobertas espaciais e muito mais. Prepare-se para uma jornada pelo cosmos!</p>
  <div class="video-display" data-aos="fade-up">
    <div class="video-wrapper">
      <video autoplay loop muted>
        <source src="vid/uni1.mp4" type="video/mp4">
        Seu navegador não suporta vídeos.
      </video>
    </div>
    <div class="video-wrapper">
      <video autoplay loop muted>
        <source src="vid/uni2.mp4" type="video/mp4">
        Seu navegador não suporta vídeos.
      </video>
    </div>
    <div class="video-wrapper">
      <video autoplay loop muted>
        <source src="vid/uni3.mp4" type="video/mp4">
        Seu navegador não suporta vídeos.
      </video>
    </div>
  </div>
</div>
<?php

 

// Recuperar as notícias
$sql = "SELECT * FROM noticias";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$noticias = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Embaralhar as notícias para que a exibição seja aleatória
shuffle($noticias);

echo '<div class="section">';
echo '<h2>Notícias em Destaque</h2>';

// Exibir as notícias aleatoriamente com alternância de layout
foreach ($noticias as $key => $noticia) {
    // Definir aleatoriamente o formato
    $layout = rand(0, 1); // 0 para imagem ao lado do texto, 1 para grade

    // Recuperar imagem
    $foto_noticia = base64_encode($noticia['foto']);
    $imagem = "data:image/jpeg;base64,{$foto_noticia}"; // Convertendo a imagem para base64

    // Criar um resumo do texto
    $texto_resumo = substr($noticia['texto'], 0, 150); // Pega os primeiros 150 caracteres do texto
    if (strlen($noticia['texto']) > 150) {
        $texto_resumo .= "..."; // Adiciona elipse se o texto for maior que 150 caracteres
    }

    // Layout com imagem ao lado do texto
    if ($layout == 0) {
        echo '<div class="news-display" data-aos="fade-up">';
        echo "<img src='{$imagem}' alt='Imagem da Notícia' class='news-image'>";
        echo '<div class="news-text">';
        echo "<h3><a href='noticia.php?slug=" . $noticia['slug'] . "'>" . htmlspecialchars($noticia['titulo']) . "</a></h3>";
        echo "<p>{$texto_resumo}</p>";
        echo '</div>';
        echo '</div>';
    }
    // Layout em formato de grade
    else {
        echo '<div class="news-grid" data-aos="fade-up">';
        echo '<div>';
        echo "<img src='{$imagem}' alt='Imagem da Notícia' class='news-image'>";
        echo "<h3><a href='noticia.php?slug=" . $noticia['slug'] . "'>" . htmlspecialchars($noticia['titulo']) . "</a></h3>";
        echo "<p>{$texto_resumo}</p>";
        echo '</div>';
        echo '</div>';
    }

    // Limitar a quantidade de notícias por seção, se necessário
    if ($key == 4) {
        break;
    }
}

echo '</div>';
?>


<!-- Nova Section -->
<section id="new-section" class="content-section" data-aos="fade-up">
  <h2>Explore o Universo</h2>
  <p>
    Descubra curiosidades incríveis sobre o espaço, as estrelas e os planetas.
    O SpaceBurst está aqui para ampliar seus horizontes.
  </p>
  <img src="img/galaxia.png" alt="Imagem de uma galáxia" class="rounded-image">
</section>

<!-- Footer -->
<footer class="site-footer">
  <button id="back-to-top">Entre e Saiba Mais</button>
</footer>


    </div>






    
      <!-- Fade -->
  <div class="fade"></div>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
  <script>
    AOS.init({
      duration: 800,
      offset: 200,
    });
  </script></div>
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
<script>
  // Seleciona o botão
  const button = document.querySelector('.btn-hover');

  // Cria o objeto de áudio para o som hover
  const hoverSound = new Audio('mousehover2.wav'); // Caminho do arquivo de som

  // Adiciona o evento para tocar o som quando o mouse passa sobre o botão
  button.addEventListener('mouseover', () => {
    hoverSound.currentTime = 0; // Reinicia o som, caso já tenha tocado antes
    hoverSound.play().catch(error => {
      console.error("Erro ao tocar o som:", error); // Se houver erro, loga no console
    });
  });
</script>

<script>
  document.getElementById('back-to-top').addEventListener('click', () => {
    window.scrollTo({
      top: 0,
      behavior: 'smooth' // Faz o scroll suave até o topo
    });
  });
</script>
<script>
  document.addEventListener('DOMContentLoaded', () => {
    const audio = document.getElementById('background-music');

    // Pausa a música quando o site sai do foco
    document.addEventListener('visibilitychange', () => {
      if (document.hidden) {
        audio.pause();
      } else {
        audio.play().catch((err) => {
          console.log("Autoplay bloqueado ao recuperar o foco:", err);
        });
      }
    });
  });
</script>


</body>
</html>
