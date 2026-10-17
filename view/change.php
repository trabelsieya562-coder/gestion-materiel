<?php session_start();
if(!isset($_SESSION['verified'])){ header("Location: pageMTPO.php"); exit(); }
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <link rel="stylesheet" href="../design/motp.css">
  <title>ToolShare — Nouveau mot de passe</title>
</head>
<body>
<div class="container">
  <div style="font-size:32px;margin-bottom:14px">🔒</div>
  <h2>Nouveau mot de passe</h2>
  <p style="font-size:13px;color:var(--text3);margin:8px 0 22px">Choisissez un nouveau mot de passe sécurisé</p>
  <?php if(!empty($_SESSION['error'])): ?>
    <div class="error">⚠ <?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
  <?php endif; ?>
  <form method="POST" action="../controleur/updatemtp.php?action=update">
    <div class="form-group" style="text-align:left;margin-bottom:14px">
      <label style="display:block;font-size:11px;font-weight:600;color:var(--text3);text-transform:uppercase;margin-bottom:6px">Nouveau mot de passe</label>
      <input type="password" name="password" class="form-control" required placeholder="Min. 8 caractères">
    </div>
    <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center;padding:12px;font-size:15px">Changer le mot de passe</button>
    <a href="Authentification.php" class="back-link">← Retour à la connexion</a>
  </form>
</div>
</body>
</html>
