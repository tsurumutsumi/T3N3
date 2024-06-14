<?php
if(!isset($_SESSION)) session_start();

$msg = "<p>名前と文章を入力して送信ボタンを押してください。</p>";

// PDOインスタンスの作成
try {
    $dbh = new PDO('mysql:host=mysql303.phy.lolipop.lan;dbname=LAA1517798-stp', 'LAA1517798', '0000');
} catch (PDOException $e) {
    die('接続エラー： ' . $e->getMessage());
}

// チャット内容の取得
$_chat = array();
$stmt = $dbh->query("select * from chat order by date desc limit 30");
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $_chat[$row["chid"]] = $row;
}

// 直近のIDをセッションに登録
$_SESSION["max_chid"] = count($_chat) ? max(array_keys($_chat)) : 0;
?>
