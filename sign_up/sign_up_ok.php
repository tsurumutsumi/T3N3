<?php
session_start();
require '../top/db-connect.php';
require '../top/header.php';
?>
<!-- アカウント作成後はログインへ飛ばす -->
<form action="create_account.php" method="post">
    <h1>かりとうろく</h1>
        <p>メールアドレス</p>
            <input type="text" name="mail">
            <br>
        <p>パスワード</p>
            <input type="password" name="password">
            <br>
        <input type="submit" value="アカウントさくせいへ">
</form>

<?php require '../top/footer.php';?>
