<?php
session_start();
require '../top/db-connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!isset($_SESSION['user']['id'])) {
        echo json_encode(['success' => false, 'message' => 'ログインが必要です。']);
        exit;
    }

    $postId = $_POST['post_id'] ?? null;
    $comment = $_POST['comment'] ?? null;
    $userId = $_SESSION['user']['id'];

    if ($postId && $comment) {
        $pdo = new PDO($connect, USER, PASS);
        $stmt = $pdo->prepare('INSERT INTO comments (comment_id,post_id, user_id, comment) VALUES (NULL,?, ?, ?)');
        $stmt->execute([$postId, $userId, $comment]);

        echo json_encode(['success' => true, 'comment' => htmlspecialchars($comment), 'user_name' => htmlspecialchars($_SESSION['user']['name']), 'comment_date' => date('Y-m-d H:i:s')]);
    } else {
        echo json_encode(['success' => false, 'message' => '無効なデータです。']);
    }
}
?>
