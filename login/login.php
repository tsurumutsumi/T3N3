<?php require '../top/header.php'; ?>
<?php require '../top/db-connect.php'; ?>
<link rel="stylesheet" href="../css/login.css">
<!-- 新規登録ボタン -->
<div class="sign">
    <input type="submit" onclick="location.href='../sign_up/tentative_register.php'" value="しんきとうろく" class="button">
</div>
<p class="title">SPT</p><!--追加(5/17) -->
<!-- ログイン -->
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
<?php require '../top/footer.php'; ?>