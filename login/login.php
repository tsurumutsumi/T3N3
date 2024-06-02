<?php 
    session_start();
    require '../top/header.php';
    require '../top/db-connect.php'; 
?>
<link rel="stylesheet" href="../css/login.css">
<link rel="stylesheet" href="../css/login.css">

<div class="log_button">
    <!-- 戻るボタン -->
    <div class="return" id="button_1">
        <button onclick="location.href='../home.php'" class="return_button" onmouseover="changeText(this, true);" onmouseout="changeText(this, false);">戻る</button>
    </div>
    <!-- 新規登録ボタン -->
    <div class="sign" id="button_2">
        <button type="submit" onclick="location.href='../sign_up/tentative_register.php'" class="sign_button" onmouseover="changeText(this, true);" onmouseout="changeText(this, false);">新規登録</button>
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
        <button type="submit" class="login_button" data-hover="▶">ログイン</button>
    </form>
</div>
<?php } ?>
<?php require '../top/footer.php'; ?>