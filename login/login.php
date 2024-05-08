<?php require '../top/header.php'; ?>
<?php require '../top/db-connect.php'; ?>
<link rel="stylesheet" href="./css/login.css">
    <form action="login_output.php" method="post">
        <h1>ログイン</h1>
        <p>メールアドレス<br><input type="text" name="mail"></p>
        <p>パスワード<br><input type="password" name="password"></p>
        <input type="submit" value="ログイン">
    </form>
<?php require '../top/footer.php'; ?>