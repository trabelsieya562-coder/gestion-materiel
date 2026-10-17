<?php
session_start();
include '../connexion/connexion.php';

if ($_GET['action'] == "update") {

    $email = $_SESSION['reset_email'];
    $password = $_POST['password'];

    $sql = "UPDATE utilisateur SET password = ? WHERE email = ?";
    $stmt = $bdd->prepare($sql);
    $stmt->execute([$password, $email]);

    unset($_SESSION['reset_email']);
    unset($_SESSION['code']);
    unset($_SESSION['verified']);

    $_SESSION['msg'] = "Password updated successfully";

    header("Location: ../view/Authentification.php");
    exit();
}