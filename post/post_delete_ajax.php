<?php
session_start();
require '../top/db-connect.php';

header('Content-Type: application/json');

// 全てのエラーを表示させる
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// ユーザーがログインしているか確認
if (!isset($_SESSION['user']['id'])) {
    echo json_encode(['success' => false, 'message' => 'ログインしてください。']);
    exit;
}

$user_id = $_SESSION['user']['id'];
if (!isset($_POST['post_id'])) {
    echo json_encode(['success' => false, 'message' => 'Post ID not provided.']);
    exit;
}

$post_id = $_POST['post_id'];

$pdo = new PDO($connect, USER, PASS);

try {
    // トランザクションを開始
    $pdo->beginTransaction();

    // 投稿の所有者を確認
    $stmt = $pdo->prepare("SELECT * FROM post_history WHERE post_id = ? AND user_id = ?");
    $stmt->execute([$post_id, $user_id]);
    $post = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$post) {
        echo json_encode(['success' => false, 'message' => '投稿が見つからないか、削除する権限がありません。']);
        $pdo->rollBack();
        exit;
    }

    // 画像ファイルが存在する場合、サーバーから削除
    if (!empty($post['picture'])) {
        $filePath = '../post_img/' . $post['picture'];
        if (file_exists($filePath)) {
            unlink($filePath);
        }
    }

    // いいね情報を削除
    $stmt = $pdo->prepare("DELETE FROM likes WHERE post_id = ?");
    $stmt->execute([$post_id]);

    // データベースから投稿を削除
    $stmt = $pdo->prepare("DELETE FROM post_history WHERE post_id = ? AND user_id = ?");
    $success = $stmt->execute([$post_id, $user_id]);

    if ($success) {
        // トランザクションをコミット
        $pdo->commit();
        echo json_encode(['success' => true, 'message' => '投稿が削除されました。']);
    } else {
        // トランザクションをロールバック
        $pdo->rollBack();
        echo json_encode(['success' => false, 'message' => '投稿の削除に失敗しました。']);
    }
} catch (Exception $e) {
    // トランザクションをロールバック
    $pdo->rollBack();
    echo json_encode(['success' => false, 'message' => 'エラーが発生しました: ' . $e->getMessage()]);
}
?>
