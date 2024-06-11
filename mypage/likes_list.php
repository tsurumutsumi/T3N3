<?php
session_start();
require '../top/db-connect.php';
require '../top/header.php';
echo '<link rel="stylesheet" href="../css/mypage.css">';
echo '<script src="../js/likes_list.js"></script>';

if (!isset($_SESSION['user']['id'])) {
    echo 'ログインが必要です。';
    exit;
}
?>
<div class="return" id="button_1">
    <button type="submit" onclick="location.href='mypage.php'" class="return_button" onmouseover="changeText(this, true);" onmouseout="changeText(this, false);">戻る</button>
</div>
<?php
$user_id = $_SESSION['user']['id'];
$pdo = new PDO($connect, USER, PASS);

$sql = $pdo->prepare('
    SELECT ph.*, u.user_name, u.icon, l.created_at AS like_date
    FROM likes l
    JOIN post_history ph ON l.post_id = ph.post_id
    JOIN user_management u ON ph.user_id = u.user_id
    WHERE l.user_id = ?
    ORDER BY l.created_at DESC
');
$sql->execute([$user_id]);

?>


<div class="likes-list">
    <h2>いいねした投稿一覧</h2>
    <?php
    echo '<ul class="post_list">';
    foreach ($sql as $row) {
        echo '<li class="post">';

        // 投稿主のマイページへ飛ばす
        echo '<a href="../mypage/mypage.php?user_id=' . htmlspecialchars($row['user_id']) . '" class="icon_image">';
        $iconPath = !empty($row['icon']) ? '../icon_img/' . htmlspecialchars($row['icon']) : '../img/no_img.png';
        echo '<img src="' . $iconPath . '" class="icon_size">';
        echo htmlspecialchars($row['user_name'] ?? '名無し');
        echo '</a><br>';

        // 画像があるかどうかチェック
        $imagePath = !empty($row['picture']) ? '../img/' . htmlspecialchars($row['picture']) : '../post_img/no_img.png';
        echo '<img src="', $imagePath, '" class="post_img"><br>';

        echo '<p class="post_comment">',htmlspecialchars($row['comment'] ?? '記載なし'), '</p><br>';

        echo htmlspecialchars($row['post_date'] ?? '日付不明'), '<br>';

        echo 'いいね日: ' . htmlspecialchars($row['like_date'] ?? '不明') . '<br>';
        echo '</li>';
    }
    echo '</ul>';
    ?>
</div>

<?php require '../top/footer.php'; ?>
