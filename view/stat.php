<?php
session_start();
include '../model/Emprunt.php';
include '../connexion/connexion.php';

$emprunt = new Emprunt(null, null, null, null, null, null, null, null, null, null);
$tousEmprunts = $emprunt->getTous();

$totalEmprunts = count($tousEmprunts);
$totalAttente  = count(array_filter($tousEmprunts, fn($e) => (string)$e['Etat']==='0' || $e['Etat']==='en attente'));
$totalValide   = count(array_filter($tousEmprunts, fn($e) => (string)$e['Etat']==='1' || $e['Etat']==='validé'));
$totalRefuse   = count(array_filter($tousEmprunts, fn($e) => (string)$e['Etat']==='2' || $e['Etat']==='refusé'));
$totalCloture  = count(array_filter($tousEmprunts, fn($e) => (string)$e['Etat']==='3' || $e['Etat']==='clôturé'));

$compteMateriel = [];
foreach ($tousEmprunts as $e) {
    $nom = $e['nom_materiel'];
    $compteMateriel[$nom] = ($compteMateriel[$nom] ?? 0) + 1;
}
arsort($compteMateriel);
$topMateriels = array_slice($compteMateriel, 0, 5, true);
$maxCount     = !empty($topMateriels) ? max($topMateriels) : 1;

$compteMembre = [];
foreach ($tousEmprunts as $e) {
    $nom = $e['nom_membre'];
    $compteMembre[$nom] = ($compteMembre[$nom] ?? 0) + 1;
}
arsort($compteMembre);
$topMembres = array_slice($compteMembre, 0, 5, true);
$maxMembre  = !empty($topMembres) ? max($topMembres) : 1;

$totalJours   = array_sum(array_column($tousEmprunts, 'nbJoursDemand'));
$moyenneJours = $totalEmprunts > 0 ? round($totalJours / $totalEmprunts, 1) : 0;
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../design/stat.css">
  <title>ToolShare — Statistiques</title>
</head>
<body>

<!-- TOPBAR -->
<nav class="topbar">
  <div class="topbar-logo">
    <div class="logo-icon">🔧</div>
    ToolShare
  </div>
  <div class="topbar-right">
    <div class="topbar-nav">
      <a href="Adminstration.php">Emprunts</a>
      <a href="gestionMatriel.php">Matériel</a>
      <a href="stat.php" class="active">Statistiques</a>
    </div>
    <a href="../controleur/logout.php" class="btn-logout">Déconnexion</a>
  </div>
</nav>

<div class="page-wrap">

  <div class="page-title">📊 Statistiques</div>
  <div class="page-subtitle">Vue d'ensemble de l'activité de l'association</div>

  <!-- STAT CARDS -->
  <div class="stats-row">
    <div class="stat-card">
      <div class="label">Total emprunts</div>
      <div class="value"><?= $totalEmprunts ?></div>
    </div>
    <div class="stat-card yellow">
      <div class="label">En attente</div>
      <div class="value"><?= $totalAttente ?></div>
    </div>
    <div class="stat-card green">
      <div class="label">Validés</div>
      <div class="value"><?= $totalValide ?></div>
    </div>
    <div class="stat-card red">
      <div class="label">Refusés</div>
      <div class="value"><?= $totalRefuse ?></div>
    </div>
    <div class="stat-card">
      <div class="label">Clôturés</div>
      <div class="value"><?= $totalCloture ?></div>
    </div>
    <div class="stat-card blue">
      <div class="label">Durée moyenne</div>
      <div class="value"><?= $moyenneJours ?></div>
      <div class="sub">jours / emprunt</div>
    </div>
  </div>

  <!-- GRAPHIQUES -->
  <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-top:8px">

    <!-- Matériel le plus emprunté -->
    <div class="card">
      <div class="section-title" style="margin-top:0">📦 Matériel le plus emprunté</div>
      <?php if(empty($topMateriels)): ?>
        <div class="empty-state"><div class="icon">📭</div>Aucune donnée.</div>
      <?php else: ?>
        <?php foreach($topMateriels as $nom => $count):
          $pct = round(($count / $maxCount) * 100);
        ?>
        <div style="margin-bottom:14px">
          <div style="display:flex;justify-content:space-between;font-size:13px;color:var(--text2);margin-bottom:5px">
            <span><?= htmlspecialchars($nom) ?></span>
            <span style="color:var(--blue);font-weight:600"><?= $count ?> emprunt<?= $count>1?'s':'' ?></span>
          </div>
          <div style="background:var(--bg2);border-radius:4px;height:8px;overflow:hidden">
            <div style="background:var(--blue);height:100%;width:<?= $pct ?>%;border-radius:4px;transition:.6s ease"></div>
          </div>
        </div>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>

    <!-- Membres les plus actifs -->
    <div class="card">
      <div class="section-title" style="margin-top:0">👤 Membres les plus actifs</div>
      <?php if(empty($topMembres)): ?>
        <div class="empty-state"><div class="icon">📭</div>Aucune donnée.</div>
      <?php else: ?>
        <?php foreach($topMembres as $nom => $count):
          $pct = round(($count / $maxMembre) * 100);
        ?>
        <div style="margin-bottom:14px">
          <div style="display:flex;justify-content:space-between;font-size:13px;color:var(--text2);margin-bottom:5px">
            <span><?= htmlspecialchars($nom) ?></span>
            <span style="color:var(--green);font-weight:600"><?= $count ?> emprunt<?= $count>1?'s':'' ?></span>
          </div>
          <div style="background:var(--bg2);border-radius:4px;height:8px;overflow:hidden">
            <div style="background:var(--green);height:100%;width:<?= $pct ?>%;border-radius:4px;transition:.6s ease"></div>
          </div>
        </div>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>

  </div>

</div>
</body>
</html>
