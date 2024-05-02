<?php require 'top/header.php'; ?>
<?php require 'top/db-connect.php'; ?>
<link rel="stylesheet" href="./css/login.css">
    <form action="rogin-output.php" method="post">
        <h1>ログイン</h1>
        メールアドレス<input type="text" name="login"><br>
        パスワード<input type="password" name="password"><br>
        <input type="submit" value="ログイン">
</form>
<?php require 'top/footer.php'; ?>