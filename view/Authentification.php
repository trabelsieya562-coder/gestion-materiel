<?php session_start(); ?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../design/Authen.css">
  <title>ToolShare — Connexion</title>
</head>
<body>

<nav class="nav-login">
  <a href="Acceuil.php" class="logo">
    <div class="logo-icon">🔧</div>
    ToolShare
  </a>
</nav>

<div class="login-card">
  <div class="login-card-header">
    <div class="icon">🔐</div>
    <h1>Se connecter</h1>
    <p>Accédez à votre espace ToolShare</p>
  </div>

  <?php
  /* Ce bloc affiche les messages stockés dans la session (msg, error, success) après une redirection.
 Il vérifie si chaque message existe, l’affiche avec un style approprié (succès ou erreur)
 puis le supprime avec unset() pour qu’il ne s’affiche qu’une seule fois (flash message)
 cette methode est securise  car elle empeche  les attaques  et n'est pas visible dans l'url contrairement a la methode get*/
  if (!empty($_SESSION['msg'])): ?>
    <div class="msg-success">✔ <?= $_SESSION['msg']; unset($_SESSION['msg']); ?></div>
  <?php endif; ?>
  <?php if (isset($_SESSION['error'])): ?>
    <div class="msg-error">⚠ <?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
  <?php endif; ?>
  <?php if (isset($_SESSION['success'])): ?>
    <div class="msg-success">✔ <?= $_SESSION['success']; unset($_SESSION['success']); ?></div>
  <?php endif; ?>

  <form method="POST" action="../controleur/controleulogin.php">
    <div class="form-group">
      <label>Login</label>
      <div class="input-wrap">
        <span class="input-icon">👤</span>
        <input type="text" name="login" class="form-control"
       placeholder="Votre login"
       value="<?= $_COOKIE['login'] ?? '' ?>">
      </div>
      
    </div>
    <div class="form-group">
      <label>Mot de passe</label>
      <div class="input-wrap">
        <span class="input-icon">🔒</span>
        <input type="password" name="password" class="form-control" placeholder="Votre mot de passe">
      </div>
      <a href="pageMTPO.php" class="forgot-link">Mot de passe oublié ?</a>
    </div>
    <button type="submit" class="btn-login">Se connecter</button>
  </form>

  <p class="register-link">
    Pas encore de compte ? <a href="inscription.php">Créer un compte</a>
  </p>
</div>

</body>
</html>
