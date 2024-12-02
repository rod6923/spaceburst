<?php
// Incluindo a conexão com o banco de dados
include('../service/conexao.php');

// Iniciando a sessão
session_start();

// // Verificando se o usuário está logado e tem o cargo de 'Jornalista'
// if (!isset($_SESSION['usuario_id']) || $_SESSION['cargo'] !== 'jornalista') {
//     // Se o usuário não estiver logado ou não tiver permissão, redireciona para a página de login
//     header("Location: login.php");
//     exit();
// }

// Função para gerar o slug
function gerarSlug($titulo) {
    $slug = strtolower($titulo);
    $slug = preg_replace('/\s+/', '-', $slug);
    $slug = preg_replace('/[^a-z0-9\-]/', '', $slug);
    return $slug;
}

// Processamento do formulário de criação de notícia
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
<!DOCTYPE html>
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
  <style>
    
    /* Formulário de Postagem de Notícia */
    .form-container {
        margin-left: 120px; 
        padding: 30px;
       
        color: white;
        border-radius: 10px;
        width: 70%;
        max-width: 900px;
      
    }

    .form-container label {
        font-size: 18px;
        font-family: 'space grotesk';
        margin-bottom: 10px;
        display: block;
    }

    .form-container input, .form-container textarea {
        width: 100%;
        padding: 12px;
        margin-bottom: 20px;
        border-radius: 8px;
        border: none;
        color: rgb(136, 136, 136);
 
      background: none;
 
        font-size: 16px;
        transition: all 0.3s ease;
    }
    .form-container input:focus, .form-container textarea:focus {

          border-bottom: 1px solid rgba(64, 63, 63, 0.734);
        border-radius: 0px;
        outline: none;
    
    }
    .form-container input[type="file"] {
        padding: 0;
    }

    .form-container button {
        background: #6a0dad;
      color: white;
      border: none;
      padding: 10px 20px;
      margin-top: 10px;
      border-radius: 5px;
      cursor: pointer;
      font-size: 16px;
      transition: background 0.3s ease;
    }

    .form-container button:hover {
        background: #8a2be2;
    }

    /* Responsividade */
    @media screen and (max-width: 768px) {
        .navbar {
            width: 100%;
            height: auto;
            flex-direction: row;
            justify-content: space-between;
        }

        .form-container {
            margin-left: 0;
            width: 90%;
        }
    }
    .form-container input[type="file"] {
        display: none; /* Oculta o botão de arquivo original */
    }

    .file-label {
        
      color: white;
      border: none;
      padding: 10px 20px;
      margin-top: 10px;
      border-radius: 999px;
      width: 20%;
      cursor: pointer;
      font-size: 16px;
      transition: background 0.3s ease;
    }

    .file-label:hover {
        size: 1.5;
    }

    .preview-img {
        margin: 15px;
        width: 100%;
        max-width: 200px;
        border-radius: 10px;
    
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
        display: none;
    }
  </style>
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
   <div class="video-container">
      <!-- Vídeo de fundo -->
      <video class="background-video" autoplay loop muted>
        <source src="vid/universo2.mp4" type="video/mp4">
        Seu navegador não suporta vídeos.
      </video>
    
      <!-- Capa preta transparente -->
      <div class="video-overlay"></div>
   <div class="fade"> </div>

    <!-- Navbar -->
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

    <!-- Formulário para criar uma nova notícia -->
    <div class="form-container">
        <h2>Postar Nova Notícia</h2>
        <form action="admin_postar_noticia.php" method="POST" enctype="multipart/form-data">
            <label for="titulo">Título da Notícia:</label>
            <input type="text" name="titulo" required><br>

            <label for="texto">Texto da Notícia:</label>
            <textarea name="texto" required></textarea><br>

            <label for="foto_noticia">Foto Principal:</label>
            <input type="file" id="foto_noticia" name="foto_noticia" accept="image/*" required>
            <label for="foto_noticia" class="file-label">Escolher Imagem</label><br>

            <img id="preview" class="preview-img" src="" alt="Pré-visualização da Imagem">


            <button type="submit">Postar Notícia</button>
        </form>
    </div>

    <!-- Script para exibir a pré-visualização da imagem -->
    <script>
        document.getElementById('foto_noticia').addEventListener('change', function(event) {
            const file = event.target.files[0];
            const reader = new FileReader();

            reader.onload = function(e) {
                const preview = document.getElementById('preview');
                preview.src = e.target.result;
                preview.style.display = 'block'; // Exibe a imagem de pré-visualização
            };

            if (file) {
                reader.readAsDataURL(file); // Lê o arquivo como URL
            }
        });
    </script>
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
