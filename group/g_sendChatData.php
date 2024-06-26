<?php
session_start();
require '../top/db-connect.php';

$text = isset($_POST["message"]) ? $_POST["message"] : "";
$group_id = isset($_POST["groupId"]) ? $_POST["groupId"] : "";
$my_id = isset($_SESSION['user']['id']) ? $_SESSION['user']['id'] : "";

$err = array();
if (!$text) $err[] = "文章を入力してください";
if (mb_strlen($text) > 50) $err[] = "文章は50文字以内で入力してください";
if (!$group_id) $err[] = "グループIDが指定されていません";

if (!count($err)) {
    try {
        $dbh = new PDO($connect, USER, PASS);
        $stmt = $dbh->prepare("INSERT INTO group_messages (group_id, timestamp, user_id, message) VALUES (?, NOW(), ?, ?)");
        $stmt->execute([$group_id, $my_id, $text]);
    } catch (PDOException $e) {
        die('接続エラー： ' . $e->getMessage());
    }
} else {
    $msg = showerr($err);
}

function showerr($err)
{
    $msg = "<ul>";
    foreach ($err as $e) {
        $msg .= "<li>" . htmlspecialchars($e) . "</li>";
    }
    $msg .= "</ul>";
    return $msg;
}
?>
