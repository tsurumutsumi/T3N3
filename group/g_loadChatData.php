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
    $user_id = $_SESSION['user']['id'];
} else {
    echo 'グループIDが指定されていません';
    exit;
}<?php
session_start();
require '../top/db-connect.php';

$id = $_POST['id'];
$isGroup = $_POST['isGroup'] === 'true';

try {
    $conn = new PDO($connect, USER, PASS);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    if ($isGroup) {
        // グループチャットの履歴を取得
        $sql = "
            SELECT gm.user_id, u.user_name, gm.message as text, gm.timestamp as date
            FROM group_messages gm
            JOIN user_management u ON gm.user_id = u.user_id
            WHERE gm.group_id = :group_id
            ORDER BY gm.timestamp DESC";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':group_id', $id, PDO::PARAM_STR);
    } else {
        // 個人チャットの履歴を取得
        $sql = "
            SELECT user_id, my_id, text, date
            FROM chat
            WHERE (user_id = :id AND my_id = :user_id) OR (user_id = :user_id AND my_id = :id)
            ORDER BY date DESC";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_STR);
        $stmt->bindParam(':user_id', $_SESSION['user']['id'], PDO::PARAM_STR);
    }
    
    $stmt->execute();
    $chats = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $conn = null;

    foreach ($chats as $chat) {
        echo '<tr>';
        echo '<td>' . htmlspecialchars($chat['user_name'] ?? $chat['user_id'], ENT_QUOTES, 'UTF-8') . '</td>';
        echo '<td>' . htmlspecialchars($chat['text'], ENT_QUOTES, 'UTF-8') . '</td>';
        echo '<td>' . htmlspecialchars($chat['date'], ENT_QUOTES, 'UTF-8') . '</td>';
        echo '</tr>';
    }
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>


$dbh = new PDO($connect, USER, PASS);

// チャットの内容の取得
$_chat = array();
$stmt = $dbh->prepare("SELECT * FROM group_messages WHERE group_id = ? ORDER BY timestamp ASC LIMIT 30");
$stmt->execute([$group_id]);
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $_chat[$row["id"]] = $row;
}

// チャットデータの書き出し
foreach ($_chat as $val) {
    echo "<tr><td>" . htmlspecialchars($val["user_id"], ENT_QUOTES, 'UTF-8') . "</td><td>" . substr($val["timestamp"], 5, 11) . "</td><td>" . htmlspecialchars($val["message"], ENT_QUOTES, 'UTF-8') . "</td></tr>";
}
?>
