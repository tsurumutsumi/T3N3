<?php
session_start();
require '../top/db-connect.php';
require '../top/header.php';
?>
<link rel="stylesheet" href="../css/likes_list.css">
<!-- <link rel="stylesheet" href="../slick/slick.css">
<link rel="stylesheet" href="../slick/slick-theme.css"> -->
<script src="../js/likes_list.js"></script>
<?
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
    $userLikes = [];
    if (isset($_SESSION['user']['id'])) {
        $likeSql = $pdo->prepare('SELECT post_id FROM likes WHERE user_id = ?');
        $likeSql->execute([$_SESSION['user']['id']]);
        $userLikes = $likeSql->fetchAll(PDO::FETCH_COLUMN, 0);
    }
    // ユーザーがフォローした人のIDを取得
    $userFollow = [];
    if (isset($_SESSION['user']['id'])) {
        $followSql = $pdo->prepare('SELECT following_id FROM follow WHERE follower_id = ?');
        $followSql->execute([$_SESSION['user']['id']]);
        $userFollow = $followSql->fetchAll(PDO::FETCH_COLUMN, 0);
    }
?>


<div class="likes-list">
    <h2>いいねした投稿一覧</h2>
    <?php
    echo '<div class="post_list">';
    foreach ($sql as $row) {
        echo '<div class="post-1" style="flex-basis:320px;">';
        echo '<div class="post-2">';
        echo '<div class="post-3">';

        // 投稿主のマイページへ飛ばす
        echo '<a href="../mypage/mypage.php?user_id=' . htmlspecialchars($row['user_id']) . '">';
            $iconPath = !empty($row['icon']) ? '../icon_img/' . htmlspecialchars($row['icon']) : '../img/no_img.png';
            echo '<img src="' . $iconPath . '" class="like_icon">';
            echo '<div class="name">'.htmlspecialchars($row['user_name'] ?? '名無し').'</div>';
        echo '</a><br>';

        // 画像があるかどうかチェック
        $imagePath = !empty($row['picture']) ? '../img/' . htmlspecialchars($row['picture']) : '../post_img/no_img.png';
        echo '<img src="', $imagePath, '" class="post_img"><br>';

        echo '<p class="comment">',htmlspecialchars($row['comment'] ?? '記載なし'), '</p><br>';

        echo '<div class="post_date">'.htmlspecialchars($row['post_date'] ?? '日付不明'), '</div>';

        // echo '</li>';
        // いいねボタンを追加
        $likeButtonSrc = in_array($row['post_id'], $userLikes) ? '../img/mark_heart_red.png' : '../img/mark_heart_gray.png';
        echo '<input type="image" class="like-button" data-post-id="', htmlspecialchars($row['post_id'] ?? 0), '" src="', $likeButtonSrc, '" alt="いいね">';
        echo '<span class="like-count">', htmlspecialchars($row['like_count'] ?? 0), '</span>';
    
        // フォローボタンを追加
        $followButtonSrc = in_array($row['user_id'], $userFollow) ? '../img/hito_blue.png' : '../img/hito_gray.png';
        echo '<input type="image" src="', $followButtonSrc, '" class="follow-button" data-user-id="', htmlspecialchars($row['user_id']), '" alt="フォロー" width=100px,height=100px>';

        echo '</div>';
        echo '</div>';
        echo '</div>';
    }
    echo '</div>';
    ?>
</div>
<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.like-button').forEach(button => {
        button.addEventListener('click', function() {
            var postId = this.getAttribute('data-post-id');
            var action = this.src.includes('mark_heart_gray.png') ? 'like' : 'unlike'; // 画像の状態でアクションを決定

            console.log('Button clicked');  // デバッグ用
            console.log('Post ID:', postId);  // デバッグ用
            console.log('Action:', action);  // デバッグ用

            var xhr = new XMLHttpRequest();
            xhr.open("POST", "../like/like.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    console.log('Response received:', xhr.responseText);  // デバッグ用
                    var response = JSON.parse(xhr.responseText);
                    if (response.success) {
                        var likeCountSpan = button.nextElementSibling;
                        var likeCount = parseInt(likeCountSpan.textContent);
                        if (action === 'like') {
                            button.src = '../img/mark_heart_red.png'; // 画像を変更
                            likeCountSpan.textContent = likeCount + 1;
                        } else {
                            button.src = '../img/mark_heart_gray.png'; // 画像を変更
                            likeCountSpan.textContent = likeCount - 1;
                        }
                    } else {
                        alert(response.message);
                    }
                }
            };

            xhr.send("post_id=" + postId + "&action=" + action);
        });
    });
});
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.follow-button').forEach(button => {
        button.addEventListener('click', function() {
            var userId = this.getAttribute('data-user-id');
            var action = this.src.includes('hito_gray.png') ? 'follow' : 'unfollow'; // 画像の状態でアクションを決定

            // デバッグ用のログ
            console.log('Button clicked');  
            console.log('User ID:', userId);  
            console.log('Action:', action);  

            if (!userId || !action) {
                console.error('Invalid userId or action');
                return;
            }

            var xhr = new XMLHttpRequest();
            xhr.open("POST", "../follow/follow.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    console.log('Response received:', xhr.responseText);  // デバッグ用
                    var response = JSON.parse(xhr.responseText);
                    if (response.status === 'followed') {
                        document.querySelectorAll('.follow-button[data-user-id="' + userId + '"]').forEach(btn => {
                            btn.src = '../img/hito_blue.png';
                        });
                    } else if (response.status === 'unfollowed') {
                        document.querySelectorAll('.follow-button[data-user-id="' + userId + '"]').forEach(btn => {
                            btn.src = '../img/hito_gray.png';
                        });
                    } else {
                        alert(response.message);
                    }
                }
            };

            xhr.send("action=" + encodeURIComponent(action) + "&user_id=" + encodeURIComponent(userId));
        });
    });
});
</script>

<?php require '../top/footer.php'; ?>
