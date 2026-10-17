<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../design/Ac.css">
  <title>ToolShare</title>
</head>
<body>

<img id="bg-image" src="tool.png" alt="">

<nav>
  <a href="Acceuil.php" class="logo">
    <div class="logo-icon">🔧</div>
    ToolShare
  </a>
  <a href="Authentification.php" class="nav-btn">Connexion</a>
</nav>

<section class="hero">
  <div class="hero-sub" style="font-size:14px;color:rgba(255,255,255,.5);letter-spacing:.08em;text-transform:uppercase;margin-bottom:16px">
    Prêt de matériel communautaire
  </div>
  <h1 class="welcome-title">
    <span id="typing"></span><span class="cursor">|</span>
  </h1>
  
  <a href="inscription.php" class="btn">Commencer</a>
</section>
<script>
const text = "Bienvenue sur ToolShare 👋";
let i = 0;

function typeWriter(){
    if(i < text.length){
        document.getElementById("typing").innerHTML += text.charAt(i);
        i++;
        setTimeout(typeWriter, 60);
    }
}

typeWriter();
</script>
</body>
</html>
