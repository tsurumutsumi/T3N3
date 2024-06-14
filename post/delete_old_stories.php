
<?php
require '../top/db-connect.php';

// 1週間前の日付を計算
$oneWeekAgo = date("Y-m-d", strtotime("-1 week"));

// 1週間以上前のストーリーを削除
$stmt = $pdo->prepare("DELETE FROM story_history WHERE post_date < ?");
$stmt->execute([$oneWeekAgo]);


?>
