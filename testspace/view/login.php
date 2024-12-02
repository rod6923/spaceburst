<!DOCTYPE html>

<?php
require "logon.php";


?>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login - SpaceBurst</title>
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
  <style>
  @import url('https://fonts.googleapis.com/css2?family=Sofia+Sans+Extra+Condensed:ital,wght@0,1..1000;1,1..1000&family=Space+Grotesk:wght@300..700&display=swap');
  </style>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
  <style>
    /* Estilos gerais */
    body, html {
      margin: 0;
      padding: 0;
      font-family: 'Sofia sans extra condensed';
      font-size: 60px;
      text-align: left;
      overflow: hidden;
      height: 100%;
      display: flex;
    }

    /* Vídeo de fundo principal */
    #background-video {
      position: fixed;
      top: 0;

      width: 100%;
      height: 100%;
      object-fit: cover;
      z-index: -2;
    }

    /* Vídeo auxiliar do lado direito */
    #side-video {
      position: fixed;

      height: 100%;
      width: 100%; /* Ocupa metade da tela */
      object-fit: cover;
      z-index: -1;
    }

    /* Fade sobre a parte direita */
    .fade-overlay {
      position: fixed;
      top: 0;
      right: 0%;
      height: 100%;
      width: 100%; /* Ocupa a mesma área do vídeo à direita */
      background: linear-gradient(to right, rgb(0, 0, 0), rgb(0, 0, 0) , rgba(0, 0, 0, 0.913),rgba(0, 0, 0, 0.445));
      z-index: -1;
    }

    /* Contêiner principal */
    .container {
      display: flex;
      align-items: center;
      justify-content: flex-start;
      height: 100%;
      width: 100%;
    
      padding-left: 50px; /* Ajusta a posição do formulário */
    }

    /* Caixa de login/cadastro */
    .form-box {
      width: 100%;
    margin-left: 100px;
      max-width: 450px;
      padding: 40px;
      color: white;
      text-align: center;
      position: relative;
      overflow: hidden;
      z-index: 100;
    }

    .form-box h2 {
      margin-bottom: 20px;
      text-align: left;
      line-height: 70px;
      width: 500px;
      z-index: 20;
    }

    /* Botões de troca */
    .form-toggle {
      display: flex;
      justify-content: space-evenly;
      margin-bottom: 20px;
    }

    .form-toggle button {
      background: #29004700;
      color: rgb(136, 136, 136);
      border: none;
      padding: 10px 20px;
      border-radius: 5px;
      cursor: pointer;
      font-size: 16px;
      transition:  0.3s ease;
    }

    .form-toggle button:hover {
    border-bottom: #9500ff;
    color: #6a0dad;
    }

    /* Formulários */
    form {
      display: none;
      animation: fadeIn 0.5s ease;
    }

    form.active {
      display: block;
    }

    input {
      background-color: black;
      width: 100%;
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
      background: #8a2be2;
    }

    /* Animação */
    @keyframes fadeIn {
      from {
        opacity: 0;
        transform: translateY(-10px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }
    .btn:hover {
    background: #ffffff;
    box-shadow: 0 0 20px rgba(255, 255, 255, 0.9);
    transform: scale(1.05);
  }
  /* Botão de voltar */
.back-button {
  position: fixed;
  top: 20px;
  left: 20px;
  background: rgba(0, 0, 0, 0.6);
  color: white;
  border: none;
  padding: 10px;
  border-radius: 50%;
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 10;
  transition: background 0.3s ease;
}

.back-button:hover {
  background: rgba(255, 255, 255, 0.2);
}

.back-button i {
  font-size: 24px;
}


  </style>
</head>
<body>
    <!-- Botão de voltar -->
<button class="back-button" onclick="goBack()">
    <i class="material-icons">arrow_back</i>
  </button>
  
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
  <!-- Vídeo de fundo -->
  <video autoplay muted loop id="background-video">
    <source src="vid/universoo.mp4" type="video/mp4">
    Seu navegador não suporta vídeos.
  </video>


  <!-- Fade sobre a parte direita -->
  <div class="fade-overlay"></div>

  <!-- Contêiner principal -->
  <div class="container">
    <div class="form-box">
      
 
      <!-- Formulário de Login -->
      <form id="login-form" class="active" method="post">
        <h2>Entre e faça parte de nossa equipe</h2>
        <input name="email" type="email" placeholder="E-mail" required>
        <input name="senha" type="password" placeholder="Senha" required>   
        <!-- Botões para alternar entre login e cadastro -->
      <div class="form-toggle">
    
     <button onclick="trocar()" id="register-toggle">Não possuo conta</button>
      </div>
        <button type="submit" class="submit-btn">Entrar</button>
       
      </form>
      
      
  <!-- JavaScript -->
  <script>

    // Função para redirecionar o usuário para a página inicial
function goBack() {
  window.location.href = "index.php"; // Substitua "index.html" pelo nome do arquivo da sua página inicial
}

  </script>
   <script>

// Função para redirecionar o usuário para a página inicial
function trocar() {
window.location.href = "cadastro.php"; // Substitua "index.html" pelo nome do arquivo da sua página inicial
}

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
