<?php
    session_start();
    require '../top/db-connect.php';
    require '../top/header.php'; 
?>
<link rel="stylesheet" href="../css/home.css">
<link rel="stylesheet" href="../slick/slick.css">
<link rel="stylesheet" href="../slick/slick-theme.css">
<script src="../js/home.js"></script>
<?php
    $pdo = new PDO($connect, USER, PASS);
if(isset($_POST['keyword'])){
    $keyword=$_POST['keyword'];
    $sql = "SELECT ph.*, u.icon, (SELECT COUNT(*) FROM likes WHERE post_id = ph.post_id) AS like_count
        FROM post_history ph
        LEFT JOIN user_management u ON ph.user_id = u.user_id
        WHERE ph.user_id LIKE '%$keyword%' OR ph.comment LIKE '%$keyword%'";

    $result = $pdo->query($sql);
    // ユーザーがいいねした投稿のIDを取得
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
    <h2>検索結果</h2>
    <div class="head_3">
        <form action="../home.php" method="post">
            <button type="submit" class="home_button" data-hover="▶">HOME</button>
        </form>
    </div>
    <?php
    $image_count=0;
    foreach ($result as $row) {
        if ($image_count % 3 == 0) {
            if ($image_count != 0) {
                echo '</div>'; // 前の行を閉じる
            }
            echo '<div class="image_row">'; // 新しい行を開始
        }
    
        // 投稿主のユーザーIDを取得する
        $post_owner_id = $row['user_id'];
    
        echo '<div class="post-1" style="flex-basis:320px;">';
        echo '<div class="post-2">';
        echo '<div class="post-3">';
        echo '<a href="mypage/custom_mypage.php?user_id=' . htmlspecialchars($post_owner_id) . '">';
            // アイコン表示 (user_management.icon フィールドを使用するように更新)
            $iconPath = !empty($row['icon']) ? '../icon_img/' . htmlspecialchars($row['icon']) : '../post_img/no_image.png';
            echo '<img src="' . $iconPath . '" width="100px" height="100px" class="post_icon">';
            //ユーザー名表示
            echo '<div class="name">', htmlspecialchars($row['user_id'] ?? '不明'), '</div>';
        echo '</a>';
    
        // 画像があるかどうかチェック
        $imagePath = !empty($row['picture']) ? '../img/' . htmlspecialchars($row['picture']) : '../img/no_img.png';
        echo '<img src="', $imagePath, '" class="post_img"><br>';
    
        //コメントの表示
        echo '<div class="comment" id="comment">', htmlspecialchars($row['comment'] ?? ''), '</div><br>';
        //日付の表示
        echo htmlspecialchars($row['post_date'] ?? '日付不明'), '<br>';
    
        // いいねボタンを追加
        $likeButtonSrc = in_array($row['post_id'], $userLikes) ? '../img/mark_heart_red.png' : '../img/mark_heart_gray.png';
        echo '<input type="image" class="like-button" data-post-id="', htmlspecialchars($row['post_id'] ?? 0), '" src="', $likeButtonSrc, '" alt="いいね">';
        echo '<span class="like-count">', htmlspecialchars($row['like_count'] ?? 0), '</span>';
    
        // フォローボタンを追加
        $followButtonSrc = in_array($row['user_id'], $userFollow) ? '../img/hito_blue.png' : '../img/hito_gray.png';
        echo '<input type="image" src="', $followButtonSrc, '" class="follow-button" data-user-id="', htmlspecialchars($row['user_id']), '" alt="フォロー" width=100px,height=100px>';
        //var_dump($row['user_id']);
        echo '</div>';
        echo '</div>';
        echo '</div>';
    
        $image_count++;
    }
    if ($image_count % 3 != 0) {
        echo '</div>'; // 最後の行を閉じる
    }
    echo '</div>';
}else{
    echo '入力してください';
}
?>
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