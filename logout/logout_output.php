<?php
session_start();
require '../top/header.php';

// ログアウト処理
if(isset($_SESSION['user'])) {
    unset($_SESSION['user']);
    echo '<br><h2>ログアウトしました。</h2><br>'; 
} else {
    echo '<br><h2>すでにログアウトしています。</h2><br>';
}

echo '<a href="../home.php">ホームへ</a>';
require '../top/footer.php';
?>
