<?php
session_start();
require '../top/db-connect.php';

if (!isset($_SESSION['user']['id'])) {
    echo json_encode(['success' => false, 'message' => 'ログインが必要です。']);
    exit;
}

$user_id = $_SESSION['user']['id'];
$post_id = $_POST['post_id'];
$action = $_POST['action'];

$pdo = new PDO($connect, USER, PASS);

try {
    if ($action === 'like') {
        // いいねを追加する
        $stmt = $pdo->prepare('INSERT INTO likes (user_id, post_id) VALUES (?, ?)');
        if ($stmt->execute([$user_id, $post_id])) {
            echo json_encode(['success' => true]);
        } else {
            throw new Exception('データベースエラーが発生しました。');
        }
    } elseif ($action === 'unlike') {
        // いいねを削除する
        $stmt = $pdo->prepare('DELETE FROM likes WHERE user_id = ? AND post_id = ?');
        if ($stmt->execute([$user_id, $post_id])) {
            echo json_encode(['success' => true]);
        } else {
            throw new Exception('データベースエラーが発生しました。');
        }
    } else {
        throw new Exception('不正なアクションです。');
    }
} catch (Exception $e) {
    error_log($e->getMessage());
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>
