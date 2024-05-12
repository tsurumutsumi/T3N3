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
    $id = $_POST['id'];
    $nickname = $_POST['nickname'];
    // アップロードされたファイルの保存先ディレクトリ
    $uploadDir = '../icon_img/';
    // アップロードされたファイルの保存パス
    $iconPath = $_FILES['pic']['name'];
    // アップロードされたファイルを指定の場所に移動
    move_uploaded_file($_FILES['pic']['tmp_name'], $uploadDir . $iconPath);



    // ユーザーテーブルにデータを挿入
    $pdo = new PDO($connect, USER, PASS);
    $stmt = $pdo->prepare("INSERT INTO user_management (user_id, user_name, mail, password, icon) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$id, $nickname, $mail, $password, $iconPath]); // 保存されたファイルのパスをデータベースに保存

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
