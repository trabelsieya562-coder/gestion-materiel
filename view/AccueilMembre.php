<?php
session_start();
include '../connexion/connexion.php';
include '../model/Emprunt.php';
include '../model/Matriel.php';

if(!isset($_SESSION['id'])){
    header("Location: Authentification.php");
    exit();
}

$emprunt  = new Emprunt(null, null, null, null, null, null, null, null, null, $_SESSION['id']);
$emprunts = $emprunt->getALL();

$matriel  = new Matriel(null, null, null, null, null);
$materiels = $matriel->getDisponible();

$nom = $_SESSION['user']['nom'] ?? 'Membre';
$initiales = strtoupper(mb_substr($nom, 0, 2));
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../design/AcceuilM.css">
  <title>ToolShare — Mon Espace</title>
</head>
<body>

<!-- TOPBAR -->
<nav class="topbar">
  <a href="AccueilMembre.php" class="topbar-logo">
    <div class="logo-icon">🔧</div>
    ToolShare
  </a>
  <div class="topbar-right">
    <div class="topbar-nav">
      <a href="AccueilMembre.php" class="active">Mes emprunts</a>
    </div>
    <div class="topbar-user">
      <div class="avatar"><?= $initiales ?></div>
      <span><?= htmlspecialchars($nom) ?></span>
    </div>
    <a href="../controleur/logout.php" class="btn-logout">Déconnexion</a>
  </div>
</nav>

<div class="page-wrap">

  <div class="page-title">📦 Mon Espace</div>
  <div class="page-subtitle">Gérez vos emprunts et découvrez le matériel disponible</div>

  <!-- ── SECTION MES EMPRUNTS ── -->
  <div class="section-title">Mes emprunts</div>

  <div class="card" style="padding:0;overflow:hidden">
    <?php if(!empty($emprunts)): ?>
    <div class="table-wrap" style="border:none;border-radius:0">
      <table>
        <thead>
          <tr>
            <th>Matériel</th>
            <th>Date demande</th>
            <th>Date emprunt</th>
            <th>Nb jours</th>
            <th>Date retour</th>
            <th>Pénalité</th>
            <th>État</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach($emprunts as $e): ?>
          <tr>
            <td><?= htmlspecialchars($e['nom_materiel']) ?></td>
            <td><?= $e['dateDeman'] ? htmlspecialchars($e['dateDeman']) : '<span style="color:var(--text3)">—</span>' ?></td>
            <td><?= $e['dateEmprun'] ? htmlspecialchars($e['dateEmprun']) : '<span style="color:var(--text3)">—</span>' ?></td>
            <td><?= htmlspecialchars($e['nbJoursDemand']) ?> j</td>
            <td><?= $e['dateRetou'] ? htmlspecialchars($e['dateRetou']) : '<span style="color:var(--text3)">—</span>' ?></td>
            <td>
              <?php if($e['montantPenalit'] > 0): ?>
                <span style="color:var(--red);font-weight:600"><?= $e['montantPenalit'] ?> dt</span>
              <?php else: ?>
                <span style="color:var(--text3)">0 dt</span>
              <?php endif; ?>
            </td>
            <td>
              <?php
                $etat = (int)$e['Etat'];
                if     ($etat === 0) echo "<span class='badge badge-wait'>⏳ En attente</span>";
                elseif ($etat === 1) echo "<span class='badge badge-ok'>✅ Validé</span>";
                elseif ($etat === 2) echo "<span class='badge badge-no'>❌ Refusé</span>";
                else                 echo "<span class='badge badge-done'>📦 Rendu</span>";
              ?>
            </td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
    <?php else: ?>
    <div class="empty-state">
      <div class="icon">📋</div>
      Vous n'avez aucun emprunt pour le moment.
    </div>
    <?php endif; ?>
  </div>

  <!-- ── SECTION MATÉRIEL DISPONIBLE ── -->
  <div class="section-title">Matériel disponible</div>

  <?php if(!empty($materiels)): ?>
  <div class="mat-grid">
    <?php foreach($materiels as $m): ?>
    <div class="mat-item">
      <img src="../uplods/<?= htmlspecialchars($m['image']) ?>"
           alt="<?= htmlspecialchars($m['nom']) ?>"
           onerror="this.style.display='none'">
      <div class="mat-item-body">
        <div class="mat-item-name"><?= htmlspecialchars($m['nom']) ?></div>
        <div class="mat-item-desc"><?= htmlspecialchars($m['description']) ?></div>
        <div class="mat-item-footer">
         
          <a href="DemandeEmprunt.php?id=<?= $m['id'] ?>" class="btn btn-primary btn-sm">demander un emprunt →</a>
        </div>
      </div>
    </div>
    <?php endforeach; ?>
  </div>
  <?php else: ?>
  <div class="card">
    <div class="empty-state">
      <div class="icon">🛠️</div>
      Aucun matériel disponible pour le moment.
    </div>
  </div>
  <?php endif; ?>

</div>
</body>
</html>
