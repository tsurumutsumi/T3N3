<?php
session_start();
require '../top/db-connect.php';
require '../top/header.php';

if(isset($_POST['mail']) && isset($_POST['password'])) {
    // フォームから送信されたメールアドレスとパスワードを取得します
    $mail = $_POST['mail'];
    $password = $_POST['password'];

    // セッションにメールアドレスとパスワードを登録します
    $_SESSION['register'] = [
        'mail' => $mail,
        'password' => $password
    ];
}

?>
<!-- アカウント作成後はログインへ飛ばす -->
<form action="create_account_ok.php" method="post">
    <h1>アカウントさくせい</h1>
        <p>ユーザーID</p>
            <input type="text" name="id">
            <br>
        <p>ニックネーム</p>
            <input type="text" name="nickname">
            <br>
        <p>アイコン</p>
            <input type="file" name="icon">
            <br>
        <input type="submit" value="さくせい">
</form>

<?php require '../top/footer.php';?>
