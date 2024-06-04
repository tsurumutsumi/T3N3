<?php
// データベース接続
require '../top/db-connect.php';

//$conn = new mysqli($servername, $username, $password, $dbname);

// エラーハンドリング
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// フォロー機能の関数
function followUser($follower_id, $following_id, $conn) {
    $sql = "INSERT INTO follow (follower_id, following_id) VALUES ($follower_id, $following_id)";
    if ($conn->query($sql) === TRUE) {
        echo "フォローしました";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// フォローを解除する関数
function unfollowUser($follower_id, $following_id, $conn) {
    $sql = "DELETE FROM follow WHERE follower_id = $follower_id AND following_id = $following_id";
    if ($conn->query($sql) === TRUE) {
        echo "フォローを解除しました";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// フォロー関数の呼び出し例
$user1_id = 1; // フォローするユーザーのID
$user2_id = 2; // フォローされるユーザーのID

followUser($user1_id, $user2_id, $conn);

// フォローを解除する場合
// unfollowUser($user1_id, $user2_id, $conn);

// データベース接続を閉じる
$conn->close();
?>
