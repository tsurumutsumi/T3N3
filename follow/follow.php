<?php
session_start();
require '../top/db-connect.php';

$response = ['success' => false, 'message' => ''];

if (!isset($_SESSION['user']['id'])) {
    $response['message'] = 'ログインしてください';
    echo json_encode($response);
    exit;
}

if (!isset($_POST['action']) || !isset($_POST['followed_id'])) {
    $response['message'] = '無効なリクエストです';
    echo json_encode($response);
    exit;
}

$action = $_POST['action'];
$followed_id = $_POST['followed_id'];
$follower_id = $_SESSION['user']['id'];

try {
    $pdo = new PDO($connect, USER, PASS);
    if ($action === 'follow') {
        $stmt = $pdo->prepare('INSERT INTO follow (follower_id, following_id) VALUES (?, ?)');
        $success = $stmt->execute([$follower_id, $followed_id]);
    } elseif ($action === 'unfollow') {
        $stmt = $pdo->prepare('DELETE FROM follow WHERE follower_id = ? AND following_id = ?');
        $success = $stmt->execute([$follower_id, $followed_id]);
    } else {
        $response['message'] = '無効なアクションです';
        echo json_encode($response);
        exit;
    }

    if ($success) {
        $response['success'] = true;
    } else {
        $response['message'] = '操作に失敗しました';
    }
} catch (PDOException $e) {
    $response['message'] = 'データベースエラー: ' . $e->getMessage();
}

echo json_encode($response);
?>
