<?php
session_start();
require '../top/db-connect.php';
require '../top/header.php';
?>
<link rel="stylesheet" href="../css/sign.css">

<!-- 追加(5/17) -->
<p class="title">SPT</p>
<!-- アカウント作成後はログインへ飛ばす -->
<!-- 仮登録 -->
<div class="back">
    <form action="create_account.php" method="post">
        <h1>アカウント作成</h1>
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
        <input type="submit" value="さくせい" class="button_1">
    </form>
</div>

<?php require '../top/footer.php';?>