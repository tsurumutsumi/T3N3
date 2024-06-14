<?php
if(!isset($_SESSION)) session_start();

// キャッシュを取らないように
header("Expires: Thu, 01 Dec 1994 16:00:00 GMT");
header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT");
header("Cache-Control: no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0",false);
header("Pragma: no-cache");
header("Content-type: text/html; charset=utf-8");

// PDOインスタンスの作成
try {
    $dbh = new PDO('mysql:host=mysql303.phy.lolipop.lan;dbname=LAA1517798-stp', 'LAA1517798', '0000');
} catch (PDOException $e) {
    die('接続エラー： ' . $e->getMessage());
}

$max_chid = isset($_SESSION["max_chid"]) ? $_SESSION["max_chid"] : 0;

// var_dump("max_chid".$max_chid);
// var_dump("_SESSIONmax_chid".$_SESSION["max_chid"]);
// exit;


// チャットの内容の取得
$_chat = array();
$stmt = $dbh->query("SELECT * FROM chat WHERE chid > $max_chid ORDER BY date DESC LIMIT 30");
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $_chat[$row["chid"]] = $row;
}

// 直近のID
$_SESSION["max_chid"] = count($_chat) ? max(array_keys($_chat)) : $max_chid;

// チャットデータの書き出し
foreach ($_chat as $val) {
    echo "<tr><td>" . htmlspecialchars($val["user_name"]) . "</td><td>" . substr($val["date"], 5, 14) . "</td><td>" . htmlspecialchars($val["text"]) . "</td></tr>";
}
?>
