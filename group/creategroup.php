<?php
session_start();
require '../top/db-connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $group_name = $_POST['group_name'];
    $user_ids = $_POST['user_ids'];
    $user_ids[] = $_SESSION['user']['id']; // 自分自身をグループに追加

    $dbh = new PDO($connect, USER, PASS);
    $dbh->beginTransaction();
    try {
        // グループを追加
        $stmt = $dbh->prepare("INSERT INTO group_chat (group_name) VALUES (?)");
        $stmt->execute([$group_name]);
        $group_id = $dbh->lastInsertId();

        // グループメンバーを追加
        $stmt = $dbh->prepare("INSERT INTO group_members (group_id, user_id) VALUES (?, ?)");
        foreach ($user_ids as $user_id) {
            $stmt->execute([$group_id, $user_id]);
        }

        // コミット
        $dbh->commit();

        // チャットページにダイレクト
        header("Location: groupchat.php?group_id=" . $group_id);
        exit;
    } catch (Exception $e) {
        // ロールバック
        $dbh->rollBack();
        echo "Failed: " . $e->getMessage();
    }
}
?>
