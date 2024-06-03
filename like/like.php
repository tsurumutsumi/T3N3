<?php
session_start();
require '../top/db-connect.php';

$response = ['success' => false, 'message' => ''];
if (!isset($_SESSION['user']['id'])) {
    $response['message'] = 'ログインしてください';
    echo json_encode($response);
    exit();
}

$userId = $_SESSION['user']['id'];
$postId = $_POST['post_id'];
$action = $_POST['action'];

$pdo = new PDO($connect, USER, PASS);

if ($action === 'like') {
    $stmt = $pdo->prepare('INSERT INTO likes (user_id, post_id) VALUES (?, ?)');
    $stmt->execute([$userId, $postId]);
    $response['success'] = true;
} elseif ($action === 'unlike') {
    $stmt = $pdo->prepare('DELETE FROM likes WHERE user_id = ? AND post_id = ?');
    $stmt->execute([$userId, $postId]);
    $response['success'] = true;
} else {
    $response['message'] = '無効なアクション';
}

echo json_encode($response);
?>
