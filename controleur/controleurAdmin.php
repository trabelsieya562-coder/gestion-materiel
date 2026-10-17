<?php


include '../connexion/connexion.php';
include '../model/Emprunt.php';

$emprunt = new Emprunt(null, null, null, null, null, null, null, null, null, null);

/* =======================
   🔴 DELETE (ANNULER)
======================= */
if (isset($_GET['action']) && $_GET['action'] === 'annuler' && isset($_GET['id'])) {

    $emprunt->supprimerEmprunt($_GET['id']);

    header("Location:../view/Adminstration.php");
    exit;
}

/* =======================
   🟡 UPDATE
======================= */
if (isset($_POST['action']) && $_POST['action'] === 'modifier') {

    $emprunt->modifierEmprunt(
        $_POST['id'],
        $_POST['dateDeman'],
        $_POST['nbJoursDemand']
    );

    header("Location:../view/Adminstration.php");
    exit;
}


?>