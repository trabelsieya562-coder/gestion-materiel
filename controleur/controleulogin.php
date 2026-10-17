<?php
session_start();
include '../connexion/connexion.php';
 include '../model/user.php';


 //  session + cookie important pour garder l'utilisateur connecté et se souvenir de son login
     // cookie pour se souvenir du login de l'utilisateur pendant 30 jours  nod5lll w n5rjjj nl9hhh maktoub wahdou
if (isset($_POST['login']) && isset($_POST['password'])) {

    $login = $_POST['login'];
    $password = $_POST['password'];

    if (empty($login) || empty($password)) {
    $_SESSION['error'] = "⚠️ Veuillez remplir tous les champs";
    header("Location: ../view/Authentification.php");
    exit();
}

    // get user
    $stmt = $bdd->prepare("SELECT * FROM utilisateur WHERE login = ?");
    $stmt->execute([$login]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
 
   // si aucun utilisateur n'est trouvé avec ce login, on redirige avec un message d'erreur
    if (!$user) {
    $_SESSION['error'] = "❌ Identifiants incorrects";
    header("Location: ../view/Authentification.php");
    exit();
}

  
// e l’authentification des utilisateurs selon leur rôle (admin, gestionnaire, membre).
// Il vérifie d’abord les champs (login et mot de passe), puis recherche l’utilisateur dans la base de données.
// Si l’utilisateur est trouvé il vérifie le mot de passe 

//  il crée la session utilisateur, enregistre un cookie pour le login, et redirige vers la page correspondant au rôle.
// En cas d’erreur (champs vides, utilisateur introuvable ou mot de passe incorrect), un message d’erreur est stocké en session puis affiché sur la page de connexion.



//le mot de passe peut être stocké en clair ou haché(pour admin et gestionnaire et membre nouveauw)  donc on utilise password_verify pour les mots de passe hachés et une comparaison directe pour les mots de passe en clair
    if (password_verify($password, $user['password']) || $password == $user['password'])  {
       
//permet de garder l’utilisateur connecté et d’accéder à ses informations dans les autres pages.
        // SESSION
        $_SESSION['id'] = $user['id'];
        $_SESSION['user'] = $user;

        // On stocke le login de l’utilisateur dans un cookie appelé "login" afin de le garder connecté ou pré-remplir le champ login lors de sa prochaine visite.
// Le cookie expire après 30 jours  
        setcookie("login", $user['login'], time() + 30 * 24 * 3600, "/");  
// inputtt de login champ de saisie affiche le login de l’utilisateur.
// Si un cookie "login" existe sa valeur est automatiquement affichée dans le champ 
// Sinon, le champ reste vide ('') grâce à l’opérateur n(??).
       
        if ($user['role'] == 2) {
            header("Location: ../view/Adminstration.php");
            exit();
        } 
        elseif ($user['role'] == 0) {
            header("Location: ../view/AccueilMembre.php");
            exit();
        } 
        elseif ($user['role'] == 1) {
            header("Location: ../view/EspaceGestionnaire.php");
            exit();
        }

    } else {
        $_SESSION['error'] = "❌ Identifiants incorrects";
header("Location: ../view/Authentification.php");
exit();
    }

} else {
   //formulaire vide
$_SESSION['error'] = "⚠️ Veuillez remplir tous les champs";
header("Location: ../view/Authentification.php");
exit();
}
?>