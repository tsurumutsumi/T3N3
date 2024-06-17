<?php
session_start();
require 'top/db-connect.php';
require 'top/header.php'; 
?>
<link rel="stylesheet" href="./css/home.css">
<link rel="stylesheet" href="slick/slick.css">
<link rel="stylesheet" href="slick/slick-theme.css">
<script src="./js/home.js"></script>

<div class="button">
    <!-- マイページボタン -->
    <div class="mypage">
        <form action="mypage/mypage.php" method="post">
            <button type="image" class="icon">
                <?php 
                if (!isset($_SESSION['user']['icon']) || empty($_SESSION['user']['icon'])) {
                    echo '<img src="icon_img/icon.png" alt="アイコン" class="iconImg">';
                } else {
                    $file_info = pathinfo($_SESSION['user']['icon']);
                    $file_name = $file_info['filename'];
                    echo '<img src="icon_img/', htmlspecialchars($file_name), '_flame.png" alt="アイコン" class="iconImg">';
                }
                ?>
            </button>
        </form>
    </div>
    <!-- ログインボタン -->
    <?php
    if (!isset($_SESSION['user']['id'])) {
        echo '<div class="login" id="button_1">';
        echo '<button type="submit" onclick="location.href=\'./login/login.php\'" class="login_button" onmouseover="changeText(this, true);" onmouseout="changeText(this, false);">ログイン</button>';
        echo '</div>';
    } else {
        // ログインしている場合の処理
        echo '<div class="logout">';
        echo '<a href="#" onclick="logoutchack()" class="logout_link">LOGOUT</a>';
        echo '</div>';
    }
    ?>
</div>

<?php
if (isset($_SESSION['user']['id'])) {
    echo '<div class="user_name">'.htmlspecialchars($_SESSION['user']['name'] ?? '名無し'), 'としてログイン中です</div>';
} else {
    echo '<div class="user_name">ログインしていません</div>';
}

$pdo = new PDO($connect, USER, PASS);

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

// 最もいいね数が多い投稿を取得
$topPostSql = $pdo->prepare('SELECT ph.*, COUNT(l.post_id) AS like_count, u.user_name AS user_name FROM post_history ph 
                            LEFT JOIN likes l ON ph.post_id = l.post_id LEFT JOIN user_management u ON ph.user_id = u.user_id GROUP BY ph.post_id 
                            ORDER BY like_count DESC, RAND() LIMIT 1');
$topPostSql->execute();
$topPost = $topPostSql->fetch();

// ストーリーを取得
$storySql = 'SELECT s.*, u.user_name, u.icon 
            FROM story_history s 
            LEFT JOIN user_management u ON s.user_id = u.user_id 
            WHERE s.post_date >= DATE_SUB(CURDATE(), INTERVAL 1 WEEK)
            ORDER BY s.post_date DESC';

$stmt = $pdo->prepare($storySql);
$stmt->execute();

$stories = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo '<!-- スライド部分は人気の投稿、自分のチャットなどを表示予定 -->';
echo '<div class="slideshow">';

if ($topPost) {
    $topPostImagePath = !empty($topPost['picture']) ? 'img/' . htmlspecialchars($topPost['picture']) : 'img/no_img.png';
    echo '<div class="slide">';
        echo '<img src="', $topPostImagePath, '" alt="最も人気のある投稿の画像">';
        echo '<div class="slide-info">';
            echo '<p>ユーザー名: ', htmlspecialchars($topPost['user_name'] ?? '名無し'), '</p>';
            echo '<p>いいね数: ', htmlspecialchars($topPost['like_count'] ?? 0), '</p>';
            echo '<p>コメント: ', htmlspecialchars($topPost['comment'] ?? ''), '</p>';
        echo '</div>';
    echo '</div>';
}
// ストーリーの表示
foreach ($stories as $story) {
    echo '<div class="story">';
    echo '<p>ユーザー名: ' . htmlspecialchars($story['user_name']) . '</p>';
    echo '<img src="img/' . htmlspecialchars($story['picture']) . '" alt="Story Image" width="auto" height="300px">';
    echo '<p>コメント: ' . htmlspecialchars($story['comment']) . '</p>';
    // 日付の表示
    $postDate = date("Y-m-d", strtotime($story['post_date']));
    echo '<p>投稿日時: ' . htmlspecialchars($postDate) . '</p>';
    
    echo '</div>';
}

// // 固定の画像を表示
// echo '<div class="slide"><img src="img/kikou.png" alt="固定画像"></div>';
// echo '<div class="slide"><img src="img/teitetsu.png" alt="固定画像"></div>';
// echo '<div class="slide"><img src="img/taiiku_boushi_tate.png" alt="固定画像"></div>';
// echo '<div class="slide"><img src="img/undoukai_pyramid.png" alt="固定画像"></div>';
echo '</div>';
echo '<script src="js/jquery-3.7.0.min.js"></script>';
echo '<!-- スライドショーで使うプラグイン「slick」のJavaScriptを読み込む -->';
echo '<script src="slick/slick.min.js"></script>';
echo '<script src="js/slideshow.js"></script>';
echo '<br><br><br>';

echo '<!-- ↓投稿表示部分 -->';
echo '<!-- 全ユーザーの投稿 -->';
echo '<div class="post_list">';
$sql = $pdo->prepare('
    SELECT ph.*, u.icon, (SELECT COUNT(*) FROM likes WHERE post_id = ph.post_id) AS like_count
    FROM post_history ph
    LEFT JOIN user_management u ON ph.user_id = u.user_id
');
$sql->execute();

$image_count = 0;

foreach ($sql as $row) {
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
        $iconPath = !empty($row['icon']) ? 'icon_img/' . htmlspecialchars($row['icon']) : 'post_img/no_image.png';
        echo '<img src="' . $iconPath . '" width="100px" height="100px" class="post_icon">';
        //ユーザー名表示
        echo '<div class="name">', htmlspecialchars($row['user_id'] ?? '不明'), '</div>';
    echo '</a>';

    // 画像があるかどうかチェック
    $imagePath = !empty($row['picture']) ? 'img/' . htmlspecialchars($row['picture']) : 'img/no_img.png';
    echo '<img src="', $imagePath, '" class="post_img"><br>';

    //コメントの表示
    echo '<div class="comment" id="comment">', htmlspecialchars($row['comment'] ?? ''), '</div><br>';
    //日付の表示
    echo htmlspecialchars($row['post_date'] ?? '日付不明'), '<br>';

    // いいねボタンを追加
    $likeButtonSrc = in_array($row['post_id'], $userLikes) ? 'img/mark_heart_red.png' : 'img/mark_heart_gray.png';
    echo '<input type="image" class="like-button" data-post-id="', htmlspecialchars($row['post_id'] ?? 0), '" src="', $likeButtonSrc, '" alt="いいね">';
    echo '<span class="like-count">', htmlspecialchars($row['like_count'] ?? 0), '</span>';

    // フォローボタンを追加
    $followButtonSrc = in_array($row['user_id'], $userFollow) ? 'img/hito_blue.png' : 'img/hito_gray.png';
    echo '<input type="image" src="', $followButtonSrc, '" class="follow-button" data-user-id="', htmlspecialchars($row['user_id']), '" alt="フォロー">';
    
    echo '</div>';
    echo '</div>';
    echo '</div>';

    $image_count++;
}
if ($image_count % 3 != 0) {
    echo '</div>'; // 最後の行を閉じる
}
echo '</div>';
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
            xhr.open("POST", "like/like.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    console.log('Response received:', xhr.responseText);  // デバッグ用
                    var response = JSON.parse(xhr.responseText);
                    if (response.success) {
                        var likeCountSpan = button.nextElementSibling;
                        var likeCount = parseInt(likeCountSpan.textContent);
                        if (action === 'like') {
                            button.src = 'img/mark_heart_red.png'; // 画像を変更
                            likeCountSpan.textContent = likeCount + 1;
                        } else {
                            button.src = 'img/mark_heart_gray.png'; // 画像を変更
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

            console.log('Button clicked');  // デバッグ用
            console.log('User ID:', userId);  // デバッグ用
            console.log('Action:', action);  // デバッグ用

            var xhr = new XMLHttpRequest();
            xhr.open("POST", "follow/follow.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    console.log('Response received:', xhr.responseText);  // デバッグ用
                    var response = JSON.parse(xhr.responseText);
                    if (response.success) {
                        if (action === 'follow') {
                            button.src = 'img/hito_blue.png'; 
                        } else {
                            button.src = 'img/hito_gray.png'; 
                        }
                    } else {
                        alert(response.message);
                    }
                }
            };

            xhr.send("action=" + action + "&followed_id=" + userId);
        });
    });
});


function logoutchack() {
    if (confirm("ログアウトしますか？") ) {
        window.location.href = "https://aso2201161.vivian.jp/T3N3/logout/logout_output.php";
    }
}
</script>
</div>
<div class="top">
    <a href="#"><img src="img/yajirushi_top.png" alt="TOP"></a>
</div>
<?php require 'top/footer.php'; ?>
