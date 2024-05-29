<?php
session_start();
echo '<link rel="stylesheet" href="../css/logout.css">';
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
