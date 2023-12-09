<?php
session_start();

if (isset($_GET['action']) && $_GET['action'] == 'logout') {
    $_SESSION = array();

    session_destroy();

    header("Location: DangNhap.php");
    exit();
} else {
    echo "Invalid request!";
}
?>
