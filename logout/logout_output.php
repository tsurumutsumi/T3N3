<?php
session_start();
if (isset($_SESSION['user'])) {
    unset($_SESSION['user']);
    echo '<script type="text/javascript">';
    echo 'alert("ログアウトしました");';
    echo 'window.location.href = "../home.php";';
    echo '</script>';
    exit();
} else {
    header('Location: ../home.php');
    exit();
}
?>
