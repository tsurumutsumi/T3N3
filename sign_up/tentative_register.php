<?php
// session_start();
require '../top/db-connect.php';
require '../top/header.php';?>
<form action="sign_up.php" method="post">
    <h1>かりとうろく</h1>
    <p>メールアドレス</p>
        <input type="text" name="mail">
        <br>
    <input type="submit" value="メールそうしん">
</form>
<?php require '../top/footer.php';?>