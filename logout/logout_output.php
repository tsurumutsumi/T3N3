<?php session_start();?>
<?php require '../top/header.php';?>
<?php
    if(isset($_SESSION['customer'])){
        unset($_SESSION['customer']);
        echo '<br><h2>ログアウトしました。</h2><br>'; 
        echo '<a href="../home.php">ホームへ</a>';
    }else{
        echo '<br><h2>すでにログアウトしています。</h2><br>';
        echo '<a href="../home.php">ホームへ</a>';
    }
?>
<?php require '../top/footer.php';?>