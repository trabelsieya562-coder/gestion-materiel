// gère la déconnexion de l'utilisateur  il détruit la session en cours 
// supprime toutes les données de session  et  redirige vers la page d'authentification.
<?php
session_start();
session_destroy();

header("Location:../view/Authentification.php");
exit();
?>