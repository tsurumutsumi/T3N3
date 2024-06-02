<?php
session_start();
require '../top/db-connect.php';
require '../top/header.php';

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

<link rel="stylesheet" href="../css/mypage.css">
<script src="../js/mypage.js"></script>

<div class="likes-list">
    <h2>いいねした投稿一覧</h2>
    <?php
    $image_count = 0;

    foreach ($sql as $row) {
        if ($image_count % 3 == 0) {
            if ($image_count != 0) {
                echo '</div>'; // 前の行を閉じる
            }
            echo '<div class="image_row">'; // 新しい行を開始
        }
        echo '<div class="post">';

        // 投稿主のマイページへ飛ばす
        echo '<a href="../mypage/mypage.php?user_id=' . htmlspecialchars($row['user_id']) . '">';
        $iconPath = !empty($row['icon']) ? '../icon_img/' . htmlspecialchars($row['icon']) : '../img/no_img.png';
        echo '<img src="' . $iconPath . '" width="100px" height="100px">';
        echo htmlspecialchars($row['user_name'] ?? '名無し');
        echo '</a>';

        // 画像があるかどうかチェック
        $imagePath = !empty($row['picture']) ? '../post_img/' . htmlspecialchars($row['picture']) : '../post_img/no_img.png';
        echo '<img src="', $imagePath, '"><br>';

        echo htmlspecialchars($row['comment'] ?? '記載なし'), '<br>';

        echo htmlspecialchars($row['post_date'] ?? '日付不明'), '<br>';

        echo 'いいね日: ' . htmlspecialchars($row['like_date'] ?? '不明') . '<br>';

        echo '</div>';

        $image_count++;
    }
    if ($image_count % 3 != 0) {
        echo '</div>'; // 最後の行を閉じる
    }
    ?>
</div>

<?php require '../top/footer.php'; ?>
