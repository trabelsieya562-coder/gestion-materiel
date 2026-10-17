<?php

include '../connexion/connexion.php';
include '../model/Matriel.php';

$action = $_GET['action'] ?? $_POST['action'] ?? '';

// ===================== SUPPRIMER =====================
if ($action === 'supprimer') {

    $id = $_GET['id'];

    $matriel = new Matriel(null, null, null, null, null);
    $resultat = $matriel->supprimerMateriel($id);


    header('Location: ../view/gestionMatriel.php?msg=' . $resultat['message'] . '&status=' . $resultat['status']);
    exit;
}

    


// Ajouter un nouveau matériel
if ($action === 'ajouter') {

    $nom = $_POST['nom'];
    $description = $_POST['description'];
    $etat = $_POST['etat'] ?? 0;

    $image = '';

    if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
        $nomFichier = uniqid() . '_' . basename($_FILES['image']['name']);
        // Génère un nom unique pour éviter les doublons (uniqid) puisque n'y a pas de stockage en base de données du nom original du fichier
    // basename récupère seulement le nom du fichier sans le chemin
        move_uploaded_file($_FILES['image']['tmp_name'], '../uplods/' . $nomFichier);
       // Déplace le fichier depuis le dossier temporaire vers le dossier "uplods"
        $image = $nomFichier;
        //sauvgarde dans la base de donnees 
    }else {
        echo "❌ Erreur lors du téléchargement.";
    }

    $matriel = new Matriel(null, $nom, $description, $image, $etat);
    $res = $matriel->AjouterMateriel();

    if ($res === 'ok') {
        $msg = "Matériel ajouté avec succès";
        $status = "success";
    } elseif ($res === 'existe') {
        $msg = "Ce matériel existe déjà";
        $status = "error";
    } else {
        $msg = "Erreur lors de l'ajout";
        $status = "error";
    }

    header("Location: ../view/gestionMatriel.php?msg=$msg&status=$status");
    exit;
}

// Modifier un matériel existant
if ($action === 'modifier') {

    $id = $_POST['id'];
    $nom = $_POST['nom'];
    $description = $_POST['description'];
    $etat = $_POST['etat'] ?? 0;
    $image = $_POST['image_actuelle'] ?? '';

    if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
        $nomFichier = uniqid() . '_' . basename($_FILES['image']['name']);
        move_uploaded_file($_FILES['image']['tmp_name'], '../uplods/' . $nomFichier);
        $image = $nomFichier;
    }

    $matriel = new Matriel($id, $nom, $description, $image, $etat);
    $matriel->modifierMateriel($id);

    header("Location: ../view/gestionMatriel.php?msg=Modification réussie&status=success");
    exit;
}
?>

