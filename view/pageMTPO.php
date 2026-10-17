<?php session_start(); ?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../design/motp.css">
  <title>ToolShare — Mot de passe oublié</title>
</head>
<body>
<div class="container">
  <div style="font-size:32px;margin-bottom:14px">🔑</div>
  <h2>Mot de passe oublié</h2>
  <p style="font-size:13px;color:var(--text3);margin:8px 0 22px">Entrez votre email pour recevoir un code de réinitialisation</p>
  <?php if(!empty($_SESSION['error'])): ?>
    <div class="error">⚠ <?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
  <?php endif; ?>
  <form method="POST" action="../controleur/send_code.php?action=send">
    <div class="form-group" style="text-align:left;margin-bottom:14px">
      <label style="display:block;font-size:11px;font-weight:600;color:var(--text3);text-transform:uppercase;margin-bottom:6px">Email</label>
      <input type="email" name="email" class="form-control" required placeholder="votre@email.com">
    </div>
    <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center;padding:12px;font-size:15px">Continuer</button>
    <a href="Authentification.php" class="back-link">← Retour à la connexion</a>
  </form>
</div>
</body>
</html>
