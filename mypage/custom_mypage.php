<?php
ob_start(); // 出力バッファリングを開始
session_start();
require '../top/db-connect.php';
require '../top/header.php';
echo '<link rel="stylesheet" href="../css/mypage.css">';
 
$pdo = new PDO($connect, USER, PASS);
 
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
 
// URLパラメータからユーザーIDを取得
if (isset($_GET['user_id'])) {
    $user_id = $_GET['user_id'];
   
    // ユーザー情報を取得する
    $stmt = $pdo->prepare("SELECT user_name, icon, self_introduction FROM user_management WHERE user_id = ?");
    $stmt->execute([$user_id]);
    $user_info = $stmt->fetch(PDO::FETCH_ASSOC);
 
    // ユーザーの投稿数を取得する
    $stmt = $pdo->prepare("SELECT COUNT(*) AS post_count FROM post_history WHERE user_id = ?");
    $stmt->execute([$user_id]);
    $post_count = $stmt->fetch(PDO::FETCH_ASSOC)['post_count'];
 
    // フォロー数を取得する
    $stmt = $pdo->prepare("SELECT COUNT(*) AS follow_count FROM follow WHERE follower_id = ?");
    $stmt->execute([$user_id]);
    $follow_count = $stmt->fetch(PDO::FETCH_ASSOC)['follow_count'];
 
    // フォロワー数を取得する
    $stmt = $pdo->prepare("SELECT COUNT(*) AS follower_count FROM follow WHERE following_id = ?");
    $stmt->execute([$user_id]);
    $follower_count = $stmt->fetch(PDO::FETCH_ASSOC)['follower_count'];
 
    // ユーザーの投稿履歴といいね数を取得する
    $stmt = $pdo->prepare("
        SELECT ph.*,
               (SELECT COUNT(*) FROM likes WHERE likes.post_id = ph.post_id) AS like_count
        FROM post_history ph
        WHERE ph.user_id = ?
    ");
    $stmt->execute([$user_id]);
    $posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    echo 'ユーザーIDが指定されていません';
    exit;
}
 
ob_end_flush(); // 出力バッファリングを終了
?>
 
<div class="container">
    <?php if (isset($post_count)): ?>
        <div class="head">
            <div class="head_1">
                <?php
                    if (!empty($user_info['icon'])) {
                        $file_info = pathinfo($user_info['icon']);
                        $file_name = $file_info['filename'];
                        echo '<img src="../icon_img/', htmlspecialchars($file_name), '_flame.png" alt="アイコン" class="iconImg">';
                    } else {
                        echo '<img src="../img/no_img.png" alt="デフォルトアイコン" class="iconImg">';
                    }
                ?>
            </div>
            <div class="head_2">
                <?php echo '<p class="user_name">' . htmlspecialchars($user_info['user_name']) . '</p>' ?>
            </div>
            <div class="head_4">
                <form action="mypage.php" method="post">
                    <button type="submit" class="home_button" data-hover="▶">BACK</button>
                </form>
            </div>
            <div class="head_3">
                <form action="../home.php" method="post">
                    <button type="submit" class="home_button" data-hover="▶">HOME</button>
                </form>
            </div>
            <div class="head_6">
            <form action="../talk/chathome.php" method="get">
                <input type="hidden" name="user_id" value="<?php echo htmlspecialchars($user_id); ?>">
                <button type="submit" class="talk_button" data-hover="▶">TALK</button>
            </form>
            </div>
        </div>
        <div class="profile">
        <?php
            if (empty($user_info['self_introduction'])) {
                echo '<div class="text">bio：<span class="value">NONE</span></div>';
            } else {
                echo '<div class="text">bio：<span class="value">'.htmlspecialchars($user_info['self_introduction']).'</span></div>';
            }
        ?>
            <div class="text">POST：<span class="value"><?php echo htmlspecialchars($post_count); ?></span></div>
            <div class="text">FOLLOW：<span class="value"><?php echo htmlspecialchars($follow_count); ?></span></div>
            <div class="text">FOLLOWER：<span class="value"><?php echo htmlspecialchars($follower_count); ?></span></div>
        </div>
        <?php if (!empty($posts)): ?>
                <div class="history_title">
                    <p>POST HISTORY...</p>
                </div>
                <ul class="post_list">
                    <?php foreach ($posts as $post): ?>
                        <li class="post" id="post-<?php echo htmlspecialchars($post['post_id']); ?>">
                            <div class="post-2">
                                <div class="post-3">
                                    <p><?php echo htmlspecialchars($post['post_date']); ?></p>
                                    <?php
                                        $imagePath = !empty($post['picture']) ? '../img/' . htmlspecialchars($post['picture']) : '../img/no_img.png';
                                    ?>
                                    <img src="<?php echo $imagePath; ?>" alt="投稿画像" class="post_img">
                                    <p class="post_comment"><?php echo htmlspecialchars($post['comment']); ?></p>
                                    <?php
                                     // いいねボタンを追加
                                        $likeButtonSrc = in_array($post['post_id'], $userLikes) ? 'img/mark_heart_red.png' : 'img/mark_heart_gray.png';
                                        echo '<input type="image" class="like-button" data-post-id="', htmlspecialchars($post['post_id'] ?? 0), '" src="', $likeButtonSrc, '" alt="いいね">';
                                        echo '<span class="like-count">', htmlspecialchars($post['like_count'] ?? 0), '</span>';
 
                                     // フォローボタンを追加
                                        $followButtonSrc = in_array($post['user_id'], $userFollow) ? 'img/hito_blue.png' : 'img/hito_gray.png';
                                        echo '<input type="image" src="', $followButtonSrc, '" class="follow-button" data-user-id="', htmlspecialchars($post['user_id']), '" alt="フォロー">';
                                    ?>
                                </div>
                            </div>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
            <p>投稿がありません。</p>
            <?php endif; ?>
    <?php endif; ?>
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