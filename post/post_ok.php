<?php
session_start();
 
// データベース接続
require '../top/db-connect.php';
require '../top/header.php';
$pdo= new PDO($connect,USER,PASS);
 
// フォームから送信されたデータの受け取り
$user_id = $_SESSION['user']['id']; // ユーザーID
$comment = $_POST['comment']; // コメント
$date = date("Y-m-d H:i:s"); // 現在の日時
 
// 画像ファイルの処理 
$picture = ''; // 初期化
if(isset($_FILES['pic']) && $_FILES['pic']['error'] === UPLOAD_ERR_OK) {
    $uploadDir = '../post_img/'; // 画像のアップロード先ディレクトリ

    // 画像のアップロード先ディレクトリが存在しない場合は作成する
    if (!file_exists($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    $uploadFile = $uploadDir . basename($_FILES['pic']['name']); // 画像ファイルのパス
    // 画像ファイルを移動
    var_dump('test');
    var_dump('1'.$_FILES['pic']['tmp_name']);
    var_dump('2'.$_FILES['pic']['name']);
    var_dump('3'.basename($_FILES['pic']['name']));
    var_dump('4'.move_uploaded_file($_FILES['pic']['tmp_name'], $uploadFile));
    var_dump('5'.$uploadFile);

    move_uploaded_file($_FILES['pic']['tmp_name'], $uploadFile);
    $picture = basename($_FILES['pic']['name']); // ファイル名のみを設定

    // if(move_uploaded_file($_FILES['pic']['tmp_name'], $uploadFile)) {

    //     var_dump();
    //     $picture = basename($_FILES['pic']['name']); // ファイル名のみを設定
    // }
    exit;
}
 
// データベースへの挿入文の準備と実行
$sql = "INSERT INTO post_history (user_id, comment, picture, post_date) VALUES (?, ?, ?, ?)";
$stmt = $pdo->prepare($sql);
$stmt->execute([$user_id, $comment, $picture, $date]);
 
// 成功または失敗に応じた処理
if($stmt) {
    // 成功した場合の処理
    echo "投稿が成功しました。";

    echo '<form action="../mypage/mypage.php" method="post">';
    echo '<input type="submit" value="マイページへ" class="post">';
    echo '</form>';

    echo '<form action="../home.php" method="post">';
    echo '<input type="submit" value="ホームへ" class="post">';
    echo '</form>';
} else {
    // 失敗した場合の処理
    echo "投稿に失敗しました。";
}
?>
