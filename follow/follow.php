<?php
session_start();
require '../top/db-connect.php';

if (!isset($_SESSION['user']['id'])) {
    echo json_encode(['success' => false, 'message' => 'ログインしてください']);
    exit;
}

$action = $_POST['action'];
$followed_id = $_POST['user_id'];
$follower_id = $_SESSION['user']['id'];

$pdo = new PDO($connect, USER, PASS);

if ($action === 'follow') {
    $stmt = $pdo->prepare('INSERT INTO follows (follower_id, followed_id) VALUES (?, ?)');
    $success = $stmt->execute([$follower_id, $followed_id]);
} elseif ($action === 'unfollow') {
    $stmt = $pdo->prepare('DELETE FROM follows WHERE follower_id = ? AND followed_id = ?');
    $success = $stmt->execute([$follower_id, $followed_id]);
}

if ($success) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => '操作に失敗しました']);
}
?>
