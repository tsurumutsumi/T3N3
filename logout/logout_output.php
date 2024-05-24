<?php
session_start();
require '../top/header.php';
echo '<link rel="stylesheet" href="../css/logout.css">';

echo '<div class="back">';
    echo '<h1>ログアウト</h1>';
// ログアウト処理
if(isset($_SESSION['user'])) {
    unset($_SESSION['user']);
    echo '<br><p>ログアウトしました。</p><br>'; 
    echo '<div class="link">';
    echo '<a href="../home.php">ホームへ</a>';
    echo '</div>';
} else {
    echo '<br><p>すでにログアウトしています。</p><br>';
    echo '<div class="link">';
    echo '<a href="../home.php">ホームへ</a>';
    echo '</div>';
}
echo '</div>';
require '../top/footer.php';
?>
