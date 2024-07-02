<?php
session_start();
require '../top/db-connect.php';

header("Expires: Thu, 01 Dec 1994 16:00:00 GMT");
header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT");
header("Cache-Control: no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header("Content-type: text/html; charset=utf-8");

// POSTリクエストからgroupIdを取得
if (isset($_POST['groupId']) && isset($_SESSION['user']['id'])) {
    $group_id = $_POST['groupId'];
    echo $_POST['groupId'];
    $user_id = $_SESSION['user']['id'];
    echo 'groupId: ' . $group_id . '<br>';
    echo 'userId: ' . $user_id . '<br>';
} else {
    echo 'グループIDが指定されていません';
    exit;
}

$dbh = new PDO($connect, USER, PASS);

// チャットの内容の取得
$_chat = array();
$stmt = $dbh->prepare("SELECT * FROM group_messages WHERE group_id = ? ORDER BY timestamp ASC LIMIT 30");
$stmt->execute([$group_id]);
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $_chat[$row["id"]] = $row;
}

echo '取得したメッセージ数: ' . count($_chat) . '<br>';

// チャットデータの書き出し
foreach ($_chat as $val) {
    echo "<tr><td class='chatName'>" . htmlspecialchars($val["user_id"]) . ":</td><td class='chatText'>" . htmlspecialchars($val["message"]) . "</td></tr>";
}
?>
