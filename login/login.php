<?php 
    session_start();
    require '../top/header.php';
    require '../top/db-connect.php'; 
?>
<link rel="stylesheet" href="../css/login.css">
<!-- 新規登録ボタン -->
<div class="log_button">
    <div class="return">
        <input type="submit" onclick="location.href='../home.php'" value="マイページへ" class="button">
    </div>
    <div class="sign">
        <input type="submit" onclick="location.href='../sign_up/tentative_register.php'" value="しんきとうろく" class="button">
    </div>
</div>
<p class="title">SPT</p><!--追加(5/17) -->
<!-- ログイン -->
<?php
    if(isset($_SESSION['user']['id'])){
        echo '既に',$_SESSION['user']['name'],'としてログインしています';
        echo '<form action="../home.php" method="post" >';
            echo '<input type="submit" value="ホームへ">';
        echo '</form>';
        echo '<form action="../mypage/mypage.php" method="post" >';
            echo '<input type="submit" value="マイページへ">';
        echo '</form>';
    }
    else{
?>
<div class="back">
    <form action="login_output.php" method="post" >
        <h1>ログイン</h1>
        <div class="iptxt">
            <input type="text" name="mail" placeholder="" class="ml">
            <label>メールアドレス</label>
            <span class="focus_line"></span>
        </div>
        <div class="ippass">
            <input type="password" name="password" placeholder="" class="pw">
            <!-- 変更(5/17) -->
            <label>パスワード</label>
            <span class="focus_line"></span>
        </div>
        <input type="submit" value="ログイン" class="button_1">
    </form>
</div>
<?php } ?>
<?php require '../top/footer.php'; ?>