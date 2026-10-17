<?php
session_start();

if (isset($_GET['action']) && $_GET['action'] == "send") {

    if (empty($_POST['email'])) {
        $_SESSION['error'] = "Email required";
        header("Location: ../view/pageMTPO.php");
        exit();
    }

    // save email
    $_SESSION['reset_email'] = $_POST['email'];

    // generate code
    $_SESSION['code'] = rand(100000, 999999);

    $_SESSION['msg'] = "Code generated successfully";

    // go to code page
    header("Location: ../view/code.php");
    exit();
}