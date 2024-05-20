<?php
// session_start();
require '../top/db-connect.php';
require '../top/header.php';?>
<link rel="stylesheet" href="../css/sign.css">
<!-- 戻るボタン -->
<div class="return">
    <input type="submit" onclick="location.href='../login/login.php'" value="ログインへ" class="button">
</div>
<!-- 追加(5/17) -->
<p class="title">SPT</p>
<!-- 仮登録 -->
<div class="back">
    <form action="sign_up.php" method="post">
        <h1>かりとうろく</h1>
        <div class="iptxt">
            <input type="text" name="mail" placeholder="" class="ml">
            <label>メールアドレス</label>
            <span class="focus_line"></span>
        </div>
        <input type="submit" value="メールそうしん" class="button_1">
    </form>
</div>
<?php require '../top/footer.php';?>