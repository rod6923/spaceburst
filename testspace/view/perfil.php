<?php
session_start();
include("../service/conexao.php");  // Caminho correto para o arquivo de conexão

// Verifique se o usuário está logado
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Buscar dados do usuário
$stmt = $pdo->prepare("SELECT * FROM usuario WHERE id = :user_id");
$stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);
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
<style>/* Foto de perfil */
.profile-picture {
    position: fixed;
    top: 20px; /* Distância do topo */
    right: 20px; /* Distância da direita */
    width: 50px; /* Ajuste o tamanho da foto */
    height: 50px; /* Ajuste o tamanho da foto */
    border-radius: 50%;
    border: 3px solid rgb(49, 48, 48);
    z-index: 20; /* A foto de perfil sobre o conteúdo */
    display: flex;
    justify-content: center;
    align-items: center;
}

/* A imagem dentro da foto de perfil */
.profile-picture img {
    width: 100%;
    height: 100%;
    object-fit: cover; /* A imagem preenche a área sem distorcer */
    border-radius: 50%;
}
input {
      background: none;
      width: 30%;
      padding: 10px;
      margin: 10px 0;
      border: none;
      border-radius: 5px;
      transition: all 0.3s ease;
      color: rgb(136, 136, 136);
    }
    input::placeholder{
        font-size: 12px;

    }
    input:focus{
        border-bottom: 1px solid rgba(64, 63, 63, 0.734);
        border-radius: 0px;

        outline: none;

    }
    button.submit-btn {
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
    button.submit-btn:hover {
    background-color: #520a8e; /* Cor mais escura para o efeito de hover */
}

   /* Estilo para o botão de escolher arquivo */
.custom-file-upload {
    display: inline-block;
    padding: 10px 20px;
    font-size: 16px;
    color: white;
    background-color: #6a0dad; /* Cor de fundo */
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s ease;
    text-align: center;
}

/* Estilo para o botão quando o usuário passar o mouse (hover) */
.custom-file-upload:hover {
    background-color: #520a8e; /* Cor mais escura para o efeito de hover */
}

/* Esconde o botão original de upload */
input[type="file"] {
    display: none;
}

</style>
<body>
  
  <audio id="background-music" loop>
    <source src="musicafundo.mp3" type="audio/mpeg">
    Seu navegador não suporta o elemento de áudio.
  </audio>
<!-- Vídeo de fundo -->
<video class="background-video" autoplay loop muted>
    <source src="vid/universo2.mp4" type="video/mp4">
    Seu navegador não suporta vídeos.
</video>

<!-- Capa preta transparente -->
<div class="video-overlay"></div>


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

<!-- Conteúdo do perfil -->
<div class="content">
    <h1>Editar Perfil</h1>

    <div class="profile-container">
        <div class="profile-picture">
            <?php
            if ($user['fotoperfil']) {
                echo '<img src="data:image/jpeg;base64,' . base64_encode($user['fotoperfil']) . '" alt="Foto de Perfil">';
            } else {
                echo '<img src="img/pfp.jpg" alt="Foto de Perfil">';
            }
            ?>
        </div>
      
    </div>

    <form action="update_profile.php" method="POST" enctype="multipart/form-data">
        <div>
            <label for="new_username">Novo Nome:</label>
            <input type="text" id="new_username" name="new_username" value="<?php echo htmlspecialchars($user['username']); ?>" required>
        </div>
        
        <div>
    <label for="new_photo" class="custom-file-upload">
        Nova Foto de Perfil:
    </label>
    <input type="file" id="new_photo" name="new_photo" accept="image/*" style="display: none;">
</div>


        <button class="submit-btn" type="submit">Salvar Alterações</button>
    </form>
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
