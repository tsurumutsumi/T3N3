<?php
session_start();

// データベース接続
require '../top/db-connect.php';
require '../top/header.php';
$pdo = new PDO($connect, USER, PASS);

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
    if(move_uploaded_file($_FILES['pic']['tmp_name'], $uploadFile)) {
        $picture = basename($_FILES['pic']['name']); // ファイル名のみを設定
    } else {
        echo "<p>画像のアップロードに失敗しました。</p>";
    }
}

// データベースへの挿入文の準備と実行
$sql = "INSERT INTO post_history (post_id, user_id, comment, picture, post_date) VALUES (NULL, ?, ?, ?, ?)";
$stmt = $pdo->prepare($sql);
$success = $stmt->execute([$user_id, $comment, $picture, $date]);

// 成功または失敗に応じた処理
if($success) {
    // 成功した場合の処理
    echo "<p>投稿が成功しました。</p>";
    echo '<div class="Okbutton">';
    echo '<form action="../mypage/mypage.php" method="post">';
    echo '<input type="submit" value="マイページへ" class="post" class="mybutton">';
    echo '</form>';

    echo '<form action="../home.php" method="post">';
    echo '<input type="submit" value="ホームへ" class="post" class="homebutton">';
    echo '</form>';
    echo '</div>';
} else {
    // 失敗した場合の処理
    echo "<p>投稿に失敗しました。</p>";
}

require '../top/footer.php';
?>
