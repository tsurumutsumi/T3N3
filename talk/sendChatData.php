<?php
session_start();
require '../top/db-connect.php';

$text = isset($_POST["text"]) ? $_POST["text"] : "";
$user_id = isset($_POST["userId"]) ? $_POST["userId"] : "";
$my_id = isset($_POST["myId"]) ? $_POST["myId"] : "";

$err = array();
if (!$text) $err[] = "文章を入力してください";
if (mb_strlen($text) > 50) $err[] = "文章は50文字以内で入力してください";
if (!$user_id) $err[] = "ユーザーIDが指定されていません";

if (!count($err)) {
    try {
        $dbh = new PDO('mysql:host=mysql303.phy.lolipop.lan;dbname=LAA1517798-stp', 'LAA1517798', '0000');
        $stmt = $dbh->prepare("INSERT INTO chat (user_id, date, user_name, text) VALUES (?, NOW(), ?, ?)");
        $stmt->execute([$user_id, $my_id, $text]);
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