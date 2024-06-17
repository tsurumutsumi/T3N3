<?php
session_start();
require '../top/db-connect.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user']['id'])) {
    echo json_encode(['status' => 'error', 'message' => 'ログインしてください']);
    exit();
}

if (!isset($_POST['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => '無効なリクエストです']);
    exit();
}

$userId = $_SESSION['user']['id'];
$followingId = $_POST['user_id'];

try {
    $pdo = new PDO($connect, USER, PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // フォロー状態の確認
    $followCheckSql = $pdo->prepare('SELECT * FROM follow WHERE follower_id = ? AND following_id = ?');
    $followCheckSql->execute([$userId, $followingId]);
    $isFollowing = $followCheckSql->fetch();

    if ($isFollowing) {
        // フォローを解除
        $unfollowSql = $pdo->prepare('DELETE FROM follow WHERE follower_id = ? AND following_id = ?');
        $unfollowSql->execute([$userId, $followingId]);
        echo json_encode(['status' => 'unfollowed']);
    } else {
        // フォローする
        $followSql = $pdo->prepare('INSERT INTO follow (follower_id, following_id) VALUES (?, ?)');
        $followSql->execute([$userId, $followingId]);
        echo json_encode(['status' => 'followed']);
    }
} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => 'データベースエラー: ' . $e->getMessage()]);
}
?>
