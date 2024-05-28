<?php
session_start();
ob_start();
require '../top/db-connect.php';
require '../top/header.php';

if (isset($_SESSION['register']) && isset($_POST['id']) && isset($_POST['nickname'])) {
    // セッションからメールアドレスとパスワードを取得
    $mail = $_SESSION['register']['mail'];
    // パスワードをハッシュ化
    $password = password_hash($_SESSION['register']['password'], PASSWORD_DEFAULT);
    $id = $_POST['id'];
    $nickname = $_POST['nickname'];

    // アイコン画像のファイル名
    $iconFilename = '';

    // 事前に用意されたアイコンが選択されているかをチェック
    if (isset($_POST['icon']) && !empty($_POST['icon'])) {
        $iconFilename = basename($_POST['icon']); // フルパスからファイル名のみを取得
    }

    try {
        // ユーザーテーブルにデータを挿入
        $pdo = new PDO($connect, USER, PASS);
        $stmt = $pdo->prepare("INSERT INTO user_management (user_id, user_name, mail, password, icon) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$id, $nickname, $mail, $password, $iconFilename]); // 選択されたアイコンのファイル名をデータベースに保存

        unset($_SESSION['register']);

        // 登録が完了したらログインページにリダイレクト
        header("Location: ../login/login.php");
        exit;
    } catch (PDOException $e) {
        // エラー処理
        echo 'データベースエラー: ' . $e->getMessage();
    }
} else {
    // セッションがない、または必要なPOSTデータがない場合のエラー処理
    echo '必要なデータが揃っていません。';
}
ob_end_flush(); // バッファリング終了

require '../top/footer.php';
?>
