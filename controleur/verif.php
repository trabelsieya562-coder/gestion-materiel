<?php
session_start();

if (isset($_GET['action']) && $_GET['action'] == "verify") {

    if (!isset($_SESSION['code'])) {
        $_SESSION['error'] = "Session expired";
        header("Location: ../view/code.php");
        exit();
    }

    $userCode = $_POST['code'];

    if ($userCode == $_SESSION['code']) {

        $_SESSION['verified'] = true;

        $_SESSION['msg'] = "Code correct";

        header("Location: ../view/change.php");
        exit();

    } else {

        $_SESSION['error'] = "Wrong code";

        header("Location: ../view/code.php");
        exit();
    }
}