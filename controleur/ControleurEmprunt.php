<?php
include "../connexion/connexion.php";
include "../model/Emprunt.php";

$id = $_POST['id'];
$dateRetourReelle = $_POST['dateRetourReelle'];

// récupérer emprunt
$emprunt = Emprunt::getById($id);

if (!$emprunt) {
    echo "Emprunt introuvable";
    exit();
} else {

    $datePrevue = $emprunt['dateRetou'];

    // 1. calcul retard
    $nbJoursRetard = 0;

    if (strtotime($dateRetourReelle) > strtotime($datePrevue)) {
        $nbJoursRetard = (strtotime($dateRetourReelle) - strtotime($datePrevue)) / 86400;
    }

    // باش نتفادو décimales
    $nbJoursRetard = floor($nbJoursRetard);

    // 2. pénalité = 3 DT / jour
    $montantPenalite = $nbJoursRetard * 3;

    // 3. update BD
    Emprunt::cloturerEmprunt($id, $nbJoursRetard, $montantPenalite);

    // 4. redirection
    header("Location: ../view/EspaceGestionnaire.php");
    exit();
}
?>