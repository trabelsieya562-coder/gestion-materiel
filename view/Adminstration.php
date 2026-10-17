<?php
session_start();
include '../model/Emprunt.php';
include '../connexion/connexion.php';

$emprunt    = new Emprunt(null, null, null, null, null, null, null, null, null, null);
$search     = isset($_GET['search'])   ? trim($_GET['search'])  : '';
// Vérifie si l'utilisateur a envoyé un paramètre "search" dans l'URL
// Si oui → on récupère sa valeur en supprimant les espaces inutiles (trim)
// Sinon → on met une chaîne vide ''
$modifierId = isset($_GET['modifier']) ? (int)$_GET['modifier'] : null;
$emprunts   = $emprunt->getTous();
// Récupère tous les emprunts depuis la base de données

if ($search) {
  // // Si l'utilisateur a saisi un mot-clé dans la recherche
    $emprunts = array_filter($emprunts, function($e) use ($search) {
       // array_filter permet de filtrer le tableau des emprunts
        return stripos($e['nom_materiel'], $search) !== false
        // Vérifie si le mot-clé existe dans le nom du matériel (sans tenir compte des majuscules/minuscules)

            || stripos($e['nom_membre'],   $search) !== false;
        // Vérifie si le mot-clé existe dans le nom du membre (sans tenir compte des majuscules/minuscules)
    });
}

$empruntSelectionne = null;
if ($modifierId) {
    foreach ($emprunts as $e) {
        if ((int)$e['id'] === $modifierId) { $empruntSelectionne = $e; break; }
    }
}

$nom = $_SESSION['user']['nom'] ?? 'Admin';
// Récupère le nom de l'utilisateur depuis la session ou utilise "Admin" par défaut si non défini
$initiales = strtoupper(mb_substr($nom, 0, 2));
// mb_substr($nom, 0, 2) prend les 2 premières lettres du nom
// strtoupper() convertit ces lettres en majuscules pour les afficher dans l'avatar de la topbar
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../design/Ad.css">
  <title>ToolShare — Administration</title>
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
      <a href="Adminstration.php" class="active">Emprunts</a>
      <a href="gestionMatriel.php">Matériel</a>
      <a href="stat.php">Statistiques</a>
    </div>
    <div class="topbar-user">
      <div class="avatar" style="background:rgba(239,68,68,.15);border-color:var(--red);color:var(--red)"><?= $initiales ?></div>
      <span><?= htmlspecialchars($nom) ?></span>
    </div>
    <a href="../controleur/logout.php" class="btn-logout">Déconnexion</a>
  </div>
</nav>

<div class="page-wrap">

  <div class="page-title">⚙️ Administration</div>
  <div class="page-subtitle">Bienvenue, <strong><?= htmlspecialchars($nom) ?></strong></div>

  <!-- SEARCH -->
  <div class="search-bar">
    <form method="GET" action="<?= $_SERVER['PHP_SELF'] ?>" style="display:flex;gap:10px;width:100%">
      <input type="text" name="search" class="search-input"
             placeholder="🔍 Rechercher par membre ou matériel..."
             value="<?= htmlspecialchars($search) ?>">
      <button type="submit" class="btn btn-primary">Rechercher</button>
      <?php if($search): ?>
        <a href="<?= $_SERVER['PHP_SELF'] ?>" class="btn btn-danger">✕ Effacer</a>
      <?php endif; ?>
    </form>
  </div>

  <!-- TABLE -->
  <div class="section-title">📋 Liste des emprunts</div>

  <?php if(empty($emprunts)): ?>
    <div class="card">
      <div class="empty-state"><div class="icon">🔍</div>Aucun emprunt trouvé.</div>
    </div>
  <?php else: ?>
  <div class="card" style="padding:0;overflow:hidden">
    <div class="table-wrap" style="border:none;border-radius:0">
      <table>
        <thead>
          <tr>
            <th>#</th>
            <th>Matériel</th>
            <th>Membre</th>
            <th>Date demande</th>
            <th>Date emprunt</th>
            <th>Nb jours</th>
            <th>État</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
        <?php foreach($emprunts as $e):
          $etatVal = (string)$e['Etat'];
          $badgeClass = match($etatVal) {
            '0','en attente' => 'badge-wait',
            '1','validé'     => 'badge-ok',
            '2','refusé'     => 'badge-no',
            '3','clôturé'    => 'badge-done',
            default          => 'badge-wait'
          };
          $badgeLabel = match($etatVal) {
            '0','en attente' => '⏳ En attente',
            '1','validé'     => '✅ Validé',
            '2','refusé'     => '❌ Refusé',
            '3','clôturé'    => '📦 Clôturé',
            default          => $e['Etat']
          };
          $isActive = ($modifierId === (int)$e['id']);
        ?>
        <tr <?= $isActive ? 'style="background:rgba(245,158,11,.07)"' : '' ?>>
          <td style="color:var(--text3);font-size:12px">#<?= $e['id'] ?></td>
          <td style="font-weight:500;color:var(--text)"><?= htmlspecialchars($e['nom_materiel']) ?></td>
          <td><?= htmlspecialchars($e['nom_membre']) ?></td>
          <td><?= htmlspecialchars($e['dateDeman']) ?></td>
          <td><?= htmlspecialchars($e['dateEmprun'] ?? '—') ?></td>
          <td><?= htmlspecialchars($e['nbJoursDemand']) ?> j</td>
          <td><span class="badge <?= $badgeClass ?>"><?= $badgeLabel ?></span></td>
          <td>
            <?php if($etatVal === '0' || $e['Etat'] === 'en attente'): ?>
              <div style="display:flex;gap:6px">
                <a href="<?= $_SERVER['PHP_SELF'] ?>?modifier=<?= $e['id'] ?><?= $search ? '&search='.urlencode($search) : '' ?>"
                   class="btn btn-warning btn-sm">✏️ Modifier</a>
                <a href="../controleur/controleurAdmin.php?id=<?= $e['id'] ?>&action=annuler"
                   class="btn btn-danger btn-sm"
                   onclick="return confirm('Supprimer cet emprunt ?')">🗑 Annuler</a>
              </div>
            <?php else: ?>
              <span style="color:var(--text3);font-size:12px">—</span>
            <?php endif; ?>
          </td>
        </tr>
        <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
  <?php endif; ?>

  <!-- FORMULAIRE MODIFICATION -->
  <?php if($empruntSelectionne): ?>
  <div class="section-title">✏️ Modifier l'emprunt #<?= $empruntSelectionne['id'] ?></div>
  <div class="card" style="max-width:520px">
    <div style="display:flex;align-items:center;gap:10px;margin-bottom:20px">
      <span style="font-size:13px;color:var(--text2)">
        <b style="color:var(--text)"><?= htmlspecialchars($empruntSelectionne['nom_materiel']) ?></b>
        — <?= htmlspecialchars($empruntSelectionne['nom_membre']) ?>
      </span>
    </div>
    <form method="POST" action="../controleur/controleurAdmin.php">
      <input type="hidden" name="action" value="modifier">
      <input type="hidden" name="id" value="<?= $empruntSelectionne['id'] ?>">
      <div class="form-grid-2">
        <div class="form-group">
          <label>Date emprunt</label>
          <input type="date" name="dateEmprun" class="form-control"
                 value="<?= htmlspecialchars($empruntSelectionne['dateEmprun']) ?>" required>
        </div>
        <div class="form-group">
          <label>Nombre de jours</label>
          <input type="number" name="nbJoursDemand" class="form-control"
                 value="<?= htmlspecialchars($empruntSelectionne['nbJoursDemand']) ?>" min="1" required>
        </div>
      </div>
      <div style="display:flex;gap:10px;margin-top:6px;justify-content:flex-end">
        <a href="<?= $_SERVER['PHP_SELF'] ?><?= $search ? '?search='.urlencode($search) : '' ?>"
           class="btn">✕ Annuler</a>
        <button type="submit" class="btn btn-success">✔ Enregistrer</button>
      </div>
    </form>
  </div>
  <?php endif; ?>

</div>
</body>
</html>
