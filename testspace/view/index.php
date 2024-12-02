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

  <!-- Navbar -->
  <div class="navbar">

    <a href="#home"><i class="material-icons nav-button">home</i></a>
    <a href="#about"><i class="material-icons nav-button">info</i></a>
    <a href="#news"><i class="material-icons nav-button">newspaper</i></a>
    <a href="#contact"><i class="material-icons nav-button">mail</i></a>
  </div>

  <!-- Foto de perfil -->
  <div class="profile-picture"></div>

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

    <!-- Notícia com texto ao lado -->
    <div class="section">
      <h2>Notícia em Destaque</h2>
      <div class="news-display" data-aos="fade-up">
        <img src="img/exo.png" alt="Imagem da notícia">
        <div class="news-text">
          <h3>Descoberta de um novo exoplaneta</h3>
          
        </div>
      </div>
    </div>

    <!-- Notícias em grade -->
    <div class="section">
      <h2>Últimas Notícias</h2>
      <div class="news-grid" data-aos="fade-up">
        <div>
          <img src="img/buraco.png" alt="Notícia 1">
          <h3>Buraco negro</h3>
          <p>Utilizando o Telescópio Espacial Hubble e o Observatório de Raios-X Chandra, ambos da NASA, cientistas observaram o par de buracos negros supermassivos mais próximos um do outro já registrado por dados combinados, separados por cerca de 300 anos-luz de distância.</p>
        </div>
        <div>
          <img src="img/lujupter.jpg" alt="Notícia 2">
          <h3>Lua de Júpiter</h3>
          <p>Além de ser o maior e mais massivo planeta do sistema solar, Júpiter também possui o maior número de luas orbitando-o depois que os cientistas descobriram outras 12 luas ao seu redor, elevando o número total para 92. Com isso, Júpiter superou Saturno, que possui 83 luas confirmadas.

        </div>
      </div>

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
