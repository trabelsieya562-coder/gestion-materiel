<?php
session_start();

include '../model/user.php';
include '../connexion/connexion.php';
include '../model/Emprunt.php';

$emprunt = new Emprunt(null, null, null, null, null, null, null, null, null, null);

if (isset($_GET['action'], $_GET['id'])) {

    $id     = (int) $_GET['id'];
    $action = $_GET['action'];

    if ($action === 'valider') {

        $result = $emprunt->valider($id);

        if ($result === 1) {
            $_SESSION['error'] = "❌ Limite atteinte : ce membre a déjà 2 emprunts.";
        }
        elseif ($result === 2) {
            $_SESSION['error'] = "❌ Matériel déjà emprunté.";
        }
        elseif ($result === 0) {
            $_SESSION['success'] = "✔ Emprunt validé avec succès.";
        }
        else {
            $_SESSION['error'] = "❌ Erreur inconnue.";
        }

    } elseif ($action === 'refuser') {

        $emprunt->refuser($id);
        $_SESSION['success'] = "✔ Emprunt refusé.";
    }

    header('Location: EspaceGestionnaire.php');
    exit;
}

$enAttente = $emprunt->getEnAttente();
$valides   = $emprunt->getValides();

$nom = $_SESSION['user']['nom'] ?? 'Gestionnaire';
$initiales = strtoupper(mb_substr($nom, 0, 2));
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../design/gestionnaire.css">
  <title>ToolShare — Espace Gestionnaire</title>
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
      <a href="#attente" class="active">En attente</a>
      <a href="#cours">En cours</a>
    </div>
    <div class="topbar-user">
      <div class="avatar" style="background:rgba(245,158,11,.15);border-color:var(--yellow);color:var(--yellow)"><?= $initiales ?></div>
      <span><?= htmlspecialchars($nom) ?></span>
    </div>
    <a href="../controleur/logout.php" class="btn-logout">Déconnexion</a>
  </div>
</nav>

<div class="page-wrap">

  <!-- TOASTS -->
  <?php if(isset($_SESSION['error'])): ?>
    <div class="toast toast-error">⚠ <?= $_SESSION['error'] ?></div>
    <?php unset($_SESSION['error']); ?>
  <?php endif; ?>
  <?php if(isset($_SESSION['success'])): ?>
    <div class="toast toast-success">✔ <?= $_SESSION['success'] ?></div>
    <?php unset($_SESSION['success']); ?>
  <?php endif; ?>

  <!-- STATS RAPIDES -->
  <div class="stats-row" style="grid-template-columns:repeat(3,1fr);max-width:540px">
    <div class="stat-card yellow">
      <div class="label">En attente</div>
      <div class="value"><?= count($enAttente) ?></div>
    </div>
    <div class="stat-card blue">
      <div class="label">En cours</div>
      <div class="value"><?= count($valides) ?></div>
    </div>
    <div class="stat-card red">
      <div class="label">En retard</div>
      <div class="value">
        <?php
          $retards = 0;
          foreach($valides as $v){
              $rp = date('Y-m-d', strtotime($v['dateEmprun'].' +'.$v['nbJoursDemand'].' days'));
              if($rp < date('Y-m-d')) $retards++;
          }
          echo $retards;
        ?>
      </div>
    </div>
  </div>

  <!-- ── DEMANDES EN ATTENTE ── -->
  <div class="section-title" id="attente">⏳ Demandes en attente</div>

  <div class="card" style="padding:0;overflow:hidden">
    <?php if(empty($enAttente)): ?>
      <div class="empty-state"><div class="icon">✅</div>Aucune demande en attente.</div>
    <?php else: ?>
    <div class="table-wrap" style="border:none;border-radius:0">
      <table>
        <thead>
          <tr>
            <th>Matériel</th>
            <th>Membre</th>
            <th>Date demande</th>
            <th>Nb jours</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach($enAttente as $e): ?>
          <tr>
            <td style="font-weight:500;color:var(--text)"><?= htmlspecialchars($e['nom_materiel']) ?></td>
            <td><?= htmlspecialchars($e['nom_membre']) ?></td>
            <td><?= htmlspecialchars($e['dateDeman']) ?></td>
            <td><?= htmlspecialchars($e['nbJoursDemand']) ?> j</td>
            <td style="display:flex;gap:6px;align-items:center">
              <a href="?id=<?= $e['id'] ?>&action=valider" class="btn btn-success btn-sm">✔ Valider</a>
              <a href="?id=<?= $e['id'] ?>&action=refuser" class="btn btn-danger btn-sm"
                 onclick="return confirm('Refuser cet emprunt ?')">✘ Refuser</a>
            </td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
    <?php endif; ?>
  </div>

  <!-- ── EMPRUNTS EN COURS ── -->
  <div class="section-title" id="cours">📦 Emprunts en cours</div>

  <div class="card" style="padding:0;overflow:hidden">
    <?php if(empty($valides)): ?>
      <div class="empty-state"><div class="icon">📭</div>Aucun emprunt en cours.</div>
    <?php else: ?>
    <div class="table-wrap" style="border:none;border-radius:0">
      <table>
        <thead>
          <tr>
            <th>Matériel</th>
            <th>Membre</th>
            <th>Date demande</th>
            <th>Date emprunt</th>
            <th>Retour prévu</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach($valides as $e):
            $retourPrevu = date('Y-m-d', strtotime($e['dateEmprun'].' +'.$e['nbJoursDemand'].' days'));
            $enRetard    = $retourPrevu < date('Y-m-d');
          ?>
          <tr class="<?= $enRetard ? 'danger' : '' ?>">
            <td style="font-weight:500;color:var(--text)"><?= htmlspecialchars($e['nom_materiel']) ?></td>
            <td><?= htmlspecialchars($e['nom_membre']) ?></td>
            <td><?= htmlspecialchars($e['dateDeman']) ?></td>
            <td><?= htmlspecialchars($e['dateEmprun']) ?></td>
            <td>
              <?= htmlspecialchars($retourPrevu) ?>
              <?php if($enRetard): ?>
                <span class="badge badge-retard" style="margin-left:6px">En retard</span>
              <?php endif; ?>
            </td>
            <td>
              <a href="../view/CloturetEmprunt.php?id=<?= $e['id'] ?>" class="btn btn-primary btn-sm">
                Clôturer
              </a>
            </td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
    <?php endif; ?>
  </div>

</div>

<script>
// Auto-hide toasts after 4s
setTimeout(() => {
  document.querySelectorAll('.toast').forEach(t => {
    t.style.transition = 'opacity .4s';
    t.style.opacity = '0';
    setTimeout(() => t.remove(), 400);
  });
}, 4000);
</script>

</body>
</html>
