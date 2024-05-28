<?php 
session_start();
require 'top/db-connect.php';
require 'top/header.php'; 
?>
<link rel="stylesheet" href="./css/home.css">
<link rel="stylesheet" href="slick/slick.css">
<link rel="stylesheet" href="slick/slick-theme.css">

<?php
    if(isset($_SESSION['user']['id'])) {
        echo $_SESSION['user']['name'],'としてログイン中です';
    }else{
        echo 'ログインしていません';
    }
?>

<div class="button">
    <!-- マイページボタン -->
    <div class="mypage">
        <form action="mypage/mypage.php" method="post" >
            <button type="image" class="icon">
            <!-- 画像変更するならここ -->
            <?php 
                    if(!isset($_SESSION['user']['icon']) || empty($_SESSION['user']['icon'])){
                        echo '<img src="icon_img/icon.png" alt="アイコン">';
                    }else{
                        echo '<img src="icon_img/',$_SESSION['user']['icon'],'" alt="アイコン">'; 
                    }
            ?>
            </button>
        </form>
    </div>
    <!-- ログインボタン -->
    <div class="login">
        <input type="submit" onclick="location.href='./login/login.php'" value="ログイン" class="button_1">
    </div>
</div>

<?php
$pdo = new PDO($connect, USER, PASS);

// 最もいいね数が多い投稿を取得
$topPostSql = $pdo->prepare('SELECT ph.*, COUNT(l.post_id) AS like_count, u.user_name AS user_name FROM post_history ph LEFT JOIN likes l ON ph.post_id = l.post_id LEFT JOIN user_management u ON ph.user_id = u.id GROUP BY ph.post_id ORDER BY like_count DESC, RAND() LIMIT 1');
$topPostSql->execute();
$topPost = $topPostSql->fetch();

echo '<!-- スライド部分は人気の投稿、自分のチャットなどを表示予定 -->';
echo '<div class="slideshow">';

if ($topPost) {
    $topPostImagePath = !empty($topPost['picture']) ? 'img/' . htmlspecialchars($topPost['picture']) : 'img/no_img.png';
    echo '<div class="slide">';
    echo '<img src="', $topPostImagePath, '" alt="最も人気のある投稿の画像">';
    echo '<div class="slide-info">';
    echo '<p>ユーザー名: ', htmlspecialchars($topPost['user_name']), '</p>';
    echo '<p>いいね数: ', htmlspecialchars($topPost['like_count']), '</p>';
    echo '<p>コメント: ', htmlspecialchars($topPost['comment']), '</p>';
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
$sql = $pdo->prepare('SELECT ph.*, (SELECT COUNT(*) FROM likes WHERE post_id = ph.post_id) AS like_count FROM post_history ph');
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
    echo htmlspecialchars($row['user_id']), htmlspecialchars($row['comment']), '<br>';
    
    // 画像があるかどうかチェック
    $imagePath = !empty($row['picture']) ? 'img/' . htmlspecialchars($row['picture']) : 'img/no_img.png';
    echo '<img src="', $imagePath, '"><br>';
    
    echo htmlspecialchars($row['post_date']), '<br>';

    // Likeボタンを追加
    echo '<button class="like-button" data-post-id="', htmlspecialchars($row['post_id']), '">いいね</button>';
    echo '<span class="like-count">', htmlspecialchars($row['like_count']), '</span>';

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
            var action = this.textContent === 'いいね' ? 'like' : 'unlike';

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
                            button.textContent = 'いいねを取り消す';
                            likeCountSpan.textContent = likeCount + 1;
                        } else {
                            button.textContent = 'いいね';
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
</script>

<?php require 'top/footer.php'; ?>
