<?php
session_start();
require_once '../model/user.php';
require_once '../connexion/connexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
//trim()  sert à supprimer les espaces au début et à la fin d’une chaîne de caractères
    $login    = trim($_POST['login']);
    $nom      = trim($_POST['nom']);
    $email    = trim($_POST['email']);
    $tel      = trim($_POST['tel']);
    $password = trim($_POST['password']);

    if (empty($login) || empty($nom) || empty($email) || empty($tel) || empty($password)) {
        $_SESSION['error'] = "Tous les champs sont obligatoires.";
        header("Location: ../view/inscription.php");
        exit();
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error'] = "Email invalide.";
        header("Location: ../view/inscription.php");
        exit();
    }

    $force = 0;

    if (strlen($password) >= 8) $force++;
    if (preg_match('/[A-Z]/', $password)) $force++;
    if (preg_match('/[a-z]/', $password)) $force++;
    if (preg_match('/[0-9]/', $password)) $force++;
    if (preg_match('/[\W]/', $password)) $force++;

    if ($force <= 1) {
        $_SESSION['error'] = "Mot de passe trop faible.";
        header("Location: ../view/inscription.php");
        exit();
    }

    
    $user = new User(null, $login, $password, $nom, $email, $tel, 0);
   

    $result = $user->insertMembre();

    if ($result === "exists") {

        $_SESSION['error'] = "Login ou email déjà utilisé.";
        header("Location: ../view/inscription.php");
        exit();  

    } elseif ($result === "success") {

       $_SESSION['success'] = "Compte créé avec succès connectez vous !";
header("Location: ../view/Authentification.php?");
exit();

    } else {

        $_SESSION['error'] = "Erreur lors de la création du compte.";
        header("Location: ../view/inscription.php");
        exit();
    }
}
?>
