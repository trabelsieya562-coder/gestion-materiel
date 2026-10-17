<?php
session_start();
include '../connexion/connexion.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Récupérer les détails de l'emprunt
$stmt = $bdd->prepare("
    SELECT e.*, m.nom as nom_materiel, u.nom as nom_membre
    FROM emprunt e
    JOIN materiel m ON e.id_materiel = m.id
    JOIN utilisateur u ON e.id_membr = u.id
    WHERE e.id = ?
");
$stmt->execute([$id]);
$emprunt = $stmt->fetch(PDO::FETCH_ASSOC);

$retourPrevu = '';
$joursRetard = 0;
$penalite    = 0;

if ($emprunt && $emprunt['dateEmprun']) {
    $retourPrevu = date('d/m/Y', strtotime($emprunt['dateEmprun'] . ' +' . $emprunt['nbJoursDemand'] . ' days'));
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../design/cloture.css">
  <title>ToolShare — Clôturer l'emprunt</title>
</head>
<body>

<div class="cloture-card">
  <div class="icon">📅</div>
  <h2>Clôturer l'emprunt</h2>
  <p>Saisissez la date de retour réelle du matériel</p>

  <?php if($emprunt): ?>
  <div class="summary-box">
    <div>🔧 <b>Matériel :</b> <?= htmlspecialchars($emprunt['nom_materiel']) ?></div>
    <div>👤 <b>Membre :</b> <?= htmlspecialchars($emprunt['nom_membre']) ?></div>
    <div>📆 <b>Date emprunt :</b> <?= htmlspecialchars($emprunt['dateEmprun']) ?></div>
    <div>⏱ <b>Durée demandée :</b> <?= htmlspecialchars($emprunt['nbJoursDemand']) ?> jours</div>
    <div>📋 <b>Retour prévu :</b> <?= $retourPrevu ?></div>
  </div>
  <?php endif; ?>

  <form action="../controleur/ControleurEmprunt.php?action=cloturer" method="POST">
    <input type="hidden" name="id" value="<?= $id ?>">
    <div class="form-group">
      <label>Date de retour réelle</label>
      <input type="date" name="dateRetourReelle" class="form-control" required
             value="<?= date('Y-m-d') ?>">
    </div>
    <div id="penalite-info" class="alert alert-warning" style="display:none;margin-bottom:16px"></div>
    <div style="display:flex;gap:10px;margin-top:6px">
      <button type="submit" class="btn btn-primary" style="flex:1;justify-content:center;padding:11px">
        ✔ Valider la clôture
      </button>
      <a href="EspaceGestionnaire.php" class="btn" style="padding:11px 16px">Annuler</a>
    </div>
  </form>
</div>

<script>
// Calcul pénalité en temps réel
<?php if($emprunt && $emprunt['dateEmprun']): ?>
const retourPrevu = new Date("<?= date('Y-m-d', strtotime($emprunt['dateEmprun'].' +'.$emprunt['nbJoursDemand'].' days')) ?>");
document.querySelector('input[type="date"]').addEventListener('change', function(){
  const retourReel = new Date(this.value);
  const diff = Math.floor((retourReel - retourPrevu) / (1000*60*60*24));
  const box  = document.getElementById('penalite-info');
  if(diff > 0){
    box.style.display = 'flex';
    box.textContent = '⚠ Retard de ' + diff + ' jour(s) — Pénalité : ' + (diff * 3) + ' dt';
  } else {
    box.style.display = 'none';
  }
});
<?php endif; ?>
</script>

</body>
</html>
