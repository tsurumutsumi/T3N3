<?php
session_start();
require '../top/db-connect.php';

header("Expires: Thu, 01 Dec 1994 16:00:00 GMT");
header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT");
header("Cache-Control: no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header("Content-type: text/html; charset=utf-8");

// POSTリクエストからuserIdとmyIdを取得
if (isset($_POST['userId']) && isset($_SESSION['user']['id'])) {
    $user_id = $_POST['userId'];
    $my_id = $_SESSION['user']['id'];
} else {
    echo 'ユーザーIDが指定されていません';
    exit;
}

$dbh = new PDO($connect, USER, PASS);

// チャットの内容の取得
$_chat = array();
$stmt = $dbh->prepare("SELECT * FROM chat WHERE (user_id=? AND user_name=?) OR (user_id=? AND user_name=?) ORDER BY date ASC LIMIT 30");
$stmt->execute([$user_id, $my_id, $my_id, $user_id]);
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $_chat[$row["chid"]] = $row;
}

// チャットデータの書き出し
foreach ($_chat as $val) {
    echo "<tr><td class='chatName'>" . htmlspecialchars($val["user_name"]) . ":</td><td class='chatText'>" . htmlspecialchars($val["text"]) . "</td></tr>";
}
?>
