<?php session_start();
if(!isset($_SESSION['reset_email'])){ header("Location: pageMTPO.php"); exit(); }
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <link rel="stylesheet" href="../design/motp.css">
  <title>ToolShare — Vérification</title>
</head>
<body>
<div class="container">
  <div style="font-size:32px;margin-bottom:14px">📩</div>
  <h2>Code de vérification</h2>
  <p style="font-size:13px;color:var(--text3);margin:8px 0 22px">Entrez le code reçu par email</p>
  <?php if(!empty($_SESSION['code'])): ?>
    <div class="msg">Code de test : <b><?= $_SESSION['code'] ?></b></div>
  <?php endif; ?>
  <?php if(!empty($_SESSION['error'])): ?>
    <div class="error">⚠ <?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
  <?php endif; ?>
  <form method="POST" action="../controleur/verif.php?action=verify">
    <div class="form-group" style="text-align:left;margin-bottom:14px">
      <label style="display:block;font-size:11px;font-weight:600;color:var(--text3);text-transform:uppercase;margin-bottom:6px">Code</label>
      <input type="text" name="code" class="form-control" required placeholder="XXXXXX">
    </div>
    <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center;padding:12px;font-size:15px">Vérifier</button>
    <a href="Authentification.php" class="back-link">← Retour à la connexion</a>
  </form>
</div>
</body>
</html>
