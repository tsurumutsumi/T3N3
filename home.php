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
        <form action="mypage/mypage.php" method="post" >
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
    }
    ?>
</div>

<?php
if (isset($_SESSION['user']['id'])) {
    echo '<div class="user_name">'.htmlspecialchars($_SESSION['user']['name'] ?? '名無し'), 'としてログイン中です</div>';
} else {
    echo '<div class="user_name">ログインしていません</div>';
}
?>

<?php
$pdo = new PDO($connect, USER, PASS);

// 最もいいね数が多い投稿を取得
$topPostSql = $pdo->prepare('SELECT ph.*, COUNT(l.post_id) AS like_count, u.user_name AS user_name FROM post_history ph 
                            LEFT JOIN likes l ON ph.post_id = l.post_id LEFT JOIN user_management u ON ph.user_id = u.user_id GROUP BY ph.post_id 
                            ORDER BY like_count DESC, RAND() LIMIT 1');
$topPostSql->execute();
$topPost = $topPostSql->fetch();

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

// 固定の画像を表示
    echo '<div class="slide"><img src="img/kikou.png" alt="固定画像"></div>';
    echo '<div class="slide"><img src="img/teitetsu.png" alt="固定画像"></div>';
    echo '<div class="slide"><img src="img/taiiku_boushi_tate.png" alt="固定画像"></div>';
    echo '<div class="slide"><img src="img/undoukai_pyramid.png" alt="固定画像"></div>';
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
    echo '<div class="post">';

    // アイコン表示 (user_management.icon フィールドを使用するように更新)
    $iconPath = !empty($row['icon']) ? 'icon_img/' . htmlspecialchars($row['icon']) : 'img/no_img.png';
    echo '<img src="', $iconPath, '" width=100px height=100px class="icon-image">';

    //ユーザー名表示
    echo '<div class="name">',$row['user_id'],'</div>';

    // 画像があるかどうかチェック
    $imagePath = !empty($row['picture']) ? 'img/' . htmlspecialchars($row['picture']) : 'img/no_img.png';
    echo '<img src="', $imagePath, '"><br>';

    //コメントの表示
    echo '<div class="comment">',htmlspecialchars($row['comment'] ?? ''), '</div><br>';
    //日付の表示
    echo htmlspecialchars($row['post_date'] ?? '日付不明'), '<br>';

    // いいねボタンを追加
    echo '<input type="image" class="like-button" data-post-id="', htmlspecialchars($row['post_id'] ?? 0), '" src="img/mark_heart_gray.png" alt="いいね">';
    echo '<span class="like-count">', htmlspecialchars($row['like_count'] ?? 0), '</span>';
    echo '<input type="image" src="img/hito_gray.png" class="follow_button">';

    echo '</div>';

    $image_count++;
}
if ($image_count % 3 != 0) {
    echo '</div>'; // 最後の行を閉じる
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



document.addEventListener('DOMContentLoaded', () => {

// いいねボタンのクリックイベントを処理
document.querySelectorAll('.like-button').forEach(button => {
    button.addEventListener('click', changeLikeImage);
});

// フォローボタンのクリックイベントを処理
document.querySelectorAll('.follow_button').forEach(button => {
    button.addEventListener('click', changeFollowImage);
});
});
//いいねボタン
function changeLikeImage(event) {
var button = event.target;
if (button.src.includes('mark_heart_gray.png')) {
    button.src = 'img/mark_heart_red.png'; // 別の画像のパスに変更する
} else {
    button.src = 'img/mark_heart_gray.png'; // もう一度元の画像に戻す
}
}
//フォローボタン
function changeFollowImage(event) {
var button = event.target;
if (button.src.includes('hito_gray.png')) {
    button.src = 'img/hito_blue.png'; // 別の画像のパスに変更する
} else {
    button.src = 'img/hito_gray.png'; // もう一度元の画像に戻す
}
}

</script>

<?php require 'top/footer.php'; ?>
