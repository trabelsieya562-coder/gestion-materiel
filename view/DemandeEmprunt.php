<?php
session_start();
include '../connexion/connexion.php';
include '../model/Matriel.php';
include '../model/Emprunt.php';
//// Vérifie si id du matériel est présent dans l’URL et si l’utilisateur est connecté (session active)
if(!isset($_GET['id']) || !isset($_SESSION['id'])){
    header("Location: AccueilMembre.php");
    exit();
}
// Récupère l'ID du matériel depuis l'URL (GET)
$id_materiel = $_GET['id'];
$id_membre   = $_SESSION['id']; // ID du membre connecté depuis la session

$stmt = $bdd->prepare("SELECT * FROM materiel WHERE id = ?
and etat=0");
$stmt->execute([$id_materiel]);
$materiel = $stmt->fetch(PDO::FETCH_ASSOC);
//cas où l'utilisateur modifie l'ID dans l'URL ou choisit un matériel indisponible)
if(!$materiel){
    header("Location: AccueilMembre.php");
    exit();
}
if(isset($_POST['nb_jours'])){
  //// $nb est utilisé deux fois :
    // - pour stocker le nombre de jours demandés
    // - pour calculer la date de retour (date actuelle + nb jours
    $nb = $_POST['nb_jours'];
    $stmt = $bdd->prepare("INSERT INTO emprunt (dateDeman, nbJoursDemand, dateRetou, Etat, id_materiel, id_membr)
                           VALUES (CURDATE(), ?, DATE_ADD(CURDATE(), INTERVAL ? DAY), 0, ?, ?)");
    $stmt->execute([$nb, $nb, $id_materiel, $id_membre]);
    header("Location: AccueilMembre.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../design/Demande.css">
  <title>ToolShare — Demande d'emprunt</title>
</head>
<body>

<div class="demande-card">
  <h3>📦 Demande d'emprunt</h3>

  <div class="mat-preview">
    <span>🔧</span>
    <div>
      <div style="font-weight:600"><?= htmlspecialchars($materiel['nom']) ?></div>
      <div style="font-size:12px;color:var(--text3);margin-top:2px"><?= htmlspecialchars($materiel['description']) ?></div>
    </div>
  </div>

  <div class="alert alert-warning" style="margin-bottom:18px">
    ⚠ Maximum 2 emprunts validés simultanément. Pénalité de retard : <b>3 dt/jour</b>.
  </div>

  <form method="POST">
    <div class="form-group">
      <label>Nombre de jours souhaités</label>
      <input type="number" name="nb_jours" class="form-control"
             placeholder="ex: 7" min="1" required>
    </div>
    <div style="display:flex;gap:10px;margin-top:6px">
      <button type="submit" class="btn-demande" style="flex:1">Envoyer la demande</button>
      <a href="AccueilMembre.php" class="btn" style="flex:0 0 auto">Annuler</a>
    </div>
  </form>
</div>

</body>
</html>
