<?php
session_start();
 
// データベース接続
require '../top/db-connect.php';
require '../top/header.php';
$pdo= new PDO($connect,USER,PASS);
 
// フォームから送信されたデータの受け取り
$user_id = $_SESSION['user_id']; // ユーザーID
$comment = $_POST['comment']; // コメント
$date = date("Y-m-d H:i:s"); // 現在の日時
 
// 画像ファイルの処理
$picture = ''; // 初期化
if(isset($_FILES['pic']) && $_FILES['pic']['error'] === UPLOAD_ERR_OK) {
    $uploadDir = '../uploads/'; // 画像のアップロード先ディレクトリ
    $uploadFile = $uploadDir . basename($_FILES['pic']['name']); // 画像ファイルのパス
    // 画像ファイルを移動
    if(move_uploaded_file($_FILES['pic']['tmp_name'], $uploadFile)) {
        $picture = $uploadFile; // ファイルパスを設定
    }
}
 
// データベースへの挿入文の準備と実行
$sql = "INSERT INTO post_history (user_id, comment, picture, date) VALUES (?, ?, ?, ?)";
$stmt = $pdo->prepare($sql);
$stmt->execute([$user_id, $comment, $picture, $date]);
 
// 成功または失敗に応じた処理
if($stmt) {
    // 成功した場合の処理
    echo "投稿が成功しました。";
} else {
    // 失敗した場合の処理
    echo "投稿に失敗しました。";
}
?>
 