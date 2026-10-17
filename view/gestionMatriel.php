<?php
include '../connexion/connexion.php';
include '../model/Matriel.php';

$matriel = new Matriel(null, null, null, null, null);
$liste   = $matriel->getMateriel();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../design/Mat.css">
  <title>ToolShare — Gestion du matériel</title>
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
      <a href="gestionMatriel.php" class="active">Matériel</a>
      <a href="stat.php">Statistiques</a>
    </div>
    <a href="../controleur/logout.php" class="btn-logout">Déconnexion</a>
  </div>
</nav>

<div class="page-wrap">

  <!-- MESSAGES -->
  <?php if(isset($_GET['msg'])): ?>
    <div class="alert <?= $_GET['status']==='success' ? 'alert-success' : 'alert-danger' ?>" style="margin-bottom:16px">
      <?= htmlspecialchars($_GET['msg']) ?>
    </div>
  <?php endif; ?>

  <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:24px">
    <div>
      <div class="page-title">📦 Gestion du matériel</div>
      <div class="page-subtitle">Ajoutez, modifiez ou supprimez le matériel</div>
    </div>
    <a href="gestionMatriel.php?action=ajouter" class="btn btn-primary">➕ Nouveau matériel</a>
  </div>

  <!-- FORMULAIRES -->
  <?php if(isset($_GET['action'])): ?>

    <?php if($_GET['action'] === 'ajouter'): ?>
    <div class="section-title">➕ Nouveau matériel</div>
    <div class="card" style="max-width:480px">
      <form action="../controleur/contoleurGestionMater.php" method="POST" enctype="multipart/form-data">
        <div class="form-group">
          <label>Nom du matériel</label>
          <input type="text" name="nom" class="form-control" placeholder="ex: Perceuse électrique" required>
        </div>
        <div class="form-group">
          <label>Description</label>
          <textarea name="description" class="form-control" placeholder="Décrivez le matériel..." rows="3"></textarea>
        </div>
        <div class="form-group">
          <label>Image</label>
          <input type="file" name="image" class="form-control" accept="image/*">
        </div>
        <div style="display:flex;gap:10px;margin-top:6px">
          <button type="submit" name="action" value="ajouter" class="btn btn-primary">Ajouter</button>
          <a href="gestionMatriel.php" class="btn">Annuler</a>
        </div>
      </form>
    </div>

    <?php elseif($_GET['action'] === 'modifier' && isset($_GET['id'])): ?>
    <?php
      $id = $_GET['id'];
      $st = $bdd->prepare("SELECT * FROM materiel WHERE id=?");
      $st->execute([$id]);
      $data = $st->fetch();
    ?>
    <div class="section-title">✏️ Modifier le matériel</div>
    <div class="card" style="max-width:480px">
      <form action="../controleur/contoleurGestionMater.php" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?= $data['id'] ?>">
        <input type="hidden" name="image_actuelle" value="<?= $data['image'] ?>">
        <div class="form-group">
          <label>Nom du matériel</label>
          <input type="text" name="nom" class="form-control" value="<?= htmlspecialchars($data['nom']) ?>" required>
        </div>
        <div class="form-group">
          <label>Description</label>
          <textarea name="description" class="form-control" rows="3"><?= htmlspecialchars($data['description']) ?></textarea>
        </div>
        <div class="form-group">
          <label>Nouvelle image (optionnel)</label>
          <?php if($data['image']): ?>
            <img src="../uplods/<?= htmlspecialchars($data['image']) ?>" class="thumb" style="width:80px;height:60px;margin-bottom:8px">
          <?php endif; ?>
          <input type="file" name="image" class="form-control" accept="image/*">
        </div>
        <div style="display:flex;gap:10px;margin-top:6px">
          <button type="submit" name="action" value="modifier" class="btn btn-primary">Enregistrer</button>
          <a href="gestionMatriel.php" class="btn">Annuler</a>
        </div>
      </form>
    </div>
    <?php endif; ?>

  <?php endif; ?>

  <!-- TABLE MATÉRIELS -->
  <div class="section-title">Liste du matériel</div>
  <div class="card" style="padding:0;overflow:hidden">
    <div class="table-wrap" style="border:none;border-radius:0">
      <table>
        <thead>
          <tr>
            <th>#</th>
            <th>Nom</th>
            <th>Description</th>
            <th>Image</th>
            <th>État</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach($liste as $m): ?>
          <tr>
            <td style="color:var(--text3);font-size:12px">#<?= $m['id'] ?></td>
            <td style="font-weight:500;color:var(--text)"><?= htmlspecialchars($m['nom']) ?></td>
            <td style="max-width:200px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap">
              <?= htmlspecialchars($m['description']) ?>
            </td>
            <td>
              <?php if($m['image']): ?>
                <img src="../uplods/<?= htmlspecialchars($m['image']) ?>" class="thumb">
              <?php else: ?>
                <span style="color:var(--text3)">—</span>
              <?php endif; ?>
            </td>
            <td>
              <?= ($m['etat'] == 0)
                ? "<span class='badge badge-dispo'>Disponible</span>"
                : "<span class='badge badge-emp'>Emprunté</span>" ?>
            </td>
            <td>
              <div style="display:flex;gap:6px">
                <a href="gestionMatriel.php?action=modifier&id=<?= $m['id'] ?>" class="btn btn-warning btn-sm">✏️ Modifier</a>
                <?php if($m['etat'] == 0): ?>
                  <a href="../controleur/contoleurGestionMater.php?action=supprimer&id=<?= $m['id'] ?>"
                     class="btn btn-danger btn-sm"
                     onclick="return confirm('Supprimer ce matériel ?')">🗑 Supprimer</a>
                <?php else: ?>
                  <span class="btn btn-sm" style="opacity:.4;cursor:not-allowed">🔒 Emprunté</span>
                <?php endif; ?>
              </div>
            </td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>

</div>
</body>
</html>
