<?php
session_start();
ob_start();
require '../top/db-connect.php';
require '../top/header.php';

if(isset($_SESSION['register']) && isset($_POST['id']) && isset($_POST['nickname'])) {
    // セッションからメールアドレスとパスワードを取得
    $mail = $_SESSION['register']['mail'];
    // パスワードをハッシュ化
    $password = password_hash($_SESSION['register']['password'], PASSWORD_DEFAULT);

    // フォームからユーザーIDとニックネームを取得
    $id = $_POST['id'];
    $nickname = $_POST['nickname'];

    // ユーザーテーブルにデータを挿入
    $pdo = new PDO($connect, USER, PASS);
    $stmt = $pdo->prepare("INSERT INTO user_management (user_id, user_name, mail, password) VALUES (?, ?, ?, ?)");
    $stmt->execute([$id, $nickname, $mail, $password]);

    // セッションの登録情報を削除
    unset($_SESSION['register']);

    // 登録が完了したらログインページにリダイレクト
    header("Location: ../login/login.php");
    exit;
} else {
    // エラー処理など
}
ob_end_flush(); // バッファリング終了

require '../top/footer.php';
?>
