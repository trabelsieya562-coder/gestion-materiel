<?php session_start(); ?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../design/ins.css">
  <title>ToolShare — Créer un compte</title>
</head>
<body>

<div class="particles" id="particles"></div>

<nav class="topbar" style="position:fixed;top:0;left:0;right:0;height:var(--nav-h);display:flex;align-items:center;justify-content:space-between;padding:0 32px;background:rgba(11,17,32,.7);backdrop-filter:blur(12px);border-bottom:1px solid var(--border);z-index:100">
  <a href="Acceuil.php" class="topbar-logo" style="display:flex;align-items:center;gap:10px;font-size:17px;font-weight:700;color:var(--text);text-decoration:none">
    <div class="logo-icon">🔧</div>
    ToolShare
  </a>
  <a href="Authentification.php" style="font-size:14px;color:var(--text2)">Déjà un compte ? Connexion →</a>
</nav>

<div class="page">
  <div class="card">

    <div class="card-header">
      <div class="icon">👤</div>
      <h1>Créer un compte</h1>
      <p>Rejoignez la communauté ToolShare</p>
    </div>

    <?php if(isset($_SESSION['error'])): ?>
      <div class="alert alert-danger" style="margin-bottom:18px">
        <?= $_SESSION['error']; unset($_SESSION['error']); ?>
      </div>
    <?php endif; ?>
    <?php if(isset($_SESSION['success'])): ?>
      <div class="success-msg"><?= $_SESSION['success']; unset($_SESSION['success']); ?></div>
    <?php endif; ?>

    <form action="../controleur/Controlleurinscritt.php" method="POST">
      <div class="form-grid">

        <div class="form-group">
          <label>Login</label>
          <div class="input-wrap">
            <span class="input-icon">🔑</span>
            <input type="text" name="login" class="form-control" placeholder="ex: john92" required>
          </div>
        </div>

        <div class="form-group">
          <label>Nom complet</label>
          <div class="input-wrap">
            <span class="input-icon">👤</span>
            <input type="text" name="nom" class="form-control" placeholder="ex: John Doe" required>
          </div>
        </div>

        <div class="form-group full">
          <label>Email</label>
          <div class="input-wrap">
            <span class="input-icon">✉️</span>
            <input type="email" name="email" class="form-control" placeholder="ex: john@email.com" required>
          </div>
        </div>

        <div class="form-group full">
          <label>Téléphone</label>
          <div class="input-wrap">
            <span class="input-icon">📞</span>
            <input type="text" name="tel" class="form-control" placeholder="+216 XX XXX XXX" required>
          </div>
        </div>

        <div class="form-group full">
          <label>Mot de passe</label>
          <div class="input-wrap">
            <span class="input-icon">🔒</span>
            <input type="password" name="password" id="pw" class="form-control" placeholder="Min. 8 caractères" required>
            <button type="button" class="toggle-pw" onclick="togglePw()">👁</button>
          </div>
          <div style="margin-top:8px">
            <div id="force-bar" style="height:4px;border-radius:4px;width:0%;transition:.3s;background:var(--blue)"></div>
            <span id="force-text" style="font-size:12px;color:var(--text3)"></span>
          </div>
        </div>

      </div>

      <button type="submit" class="btn-submit">Créer mon compte</button>
    </form>

    <div class="divider">
      <div class="divider-line"></div>
      <span>ou</span>
      <div class="divider-line"></div>
    </div>

    <div class="login-link">
      Déjà inscrit ? <a href="Authentification.php">Se connecter</a>
    </div>

  </div>
</div>

<script src="password.js"></script>
</body>
</html>
