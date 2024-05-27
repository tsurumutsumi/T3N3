<?php
session_start();
require '../top/db-connect.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user']['id'])) {
    echo json_encode(['success' => false, 'message' => 'ログインしてください。']);
    exit;
}

$user_id = $_SESSION['user']['id'];
$post_id = $_POST['post_id'];
$action = $_POST['action']; // 'like' or 'unlike'

$pdo = new PDO($connect, USER, PASS);

if ($action === 'like') {
    // いいねを追加
    $stmt = $pdo->prepare("INSERT INTO likes (user_id, post_id) VALUES (?, ?)");
    $success = $stmt->execute([$user_id, $post_id]);
} else if ($action === 'unlike') {
    // いいねを削除
    $stmt = $pdo->prepare("DELETE FROM likes WHERE user_id = ? AND post_id = ?");
    $success = $stmt->execute([$user_id, $post_id]);
}

if ($success) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => '操作に失敗しました。']);
}
?>
