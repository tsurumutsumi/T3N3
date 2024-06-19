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

    // デバッグ用ログ
    error_log('POST ID: ' . $postId);
    error_log('COMMENT: ' . $comment);
    error_log('USER ID: ' . $userId);

    if ($postId && $comment) {
        try {
            $pdo = new PDO($connect, USER, PASS);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // 例外をスローする設定

            $stmt = $pdo->prepare('INSERT INTO comments (comment_id,post_id, user_id, comment, comment_date) VALUES (NULL,?, ?, ?, ?)');
            $commentDate = date('Y-m-d'); // 日付フォーマットを Y-m-d に変更
            $stmt->execute([$postId, $userId, $comment, $commentDate]);

            echo json_encode([
                'success' => true, 
                'comment' => htmlspecialchars($comment), 
                'user_name' => htmlspecialchars($_SESSION['user']['name']), 
                'comment_date' => $commentDate
            ]);
        } catch (Exception $e) {
            // エラーメッセージをログに記録
            error_log('コメントの保存中にエラーが発生しました: ' . $e->getMessage());
            echo json_encode(['success' => false, 'message' => 'コメントの保存中にエラーが発生しました: ' . $e->getMessage()]);
        }
    } else {
        echo json_encode(['success' => false, 'message' => '無効なデータです。']);
    }
}
?>
