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
                        echo '<img src=icon_img/icon.png alt="アイコン">';
                    }else{
                        echo '<img src=icon_img/',$_SESSION['user']['icon'],' alt="アイコン">'; 
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

echo '<!-- スライド部分は人気の投稿、自分のチャットなどを表示予定 -->';
echo '<div class="slideshow">';
echo '<!-- imagesフォルダ内にある画像を表示  -->';
echo '<!-- ここに4行追加 -->';
echo '<img src="img/kikou.png">';
echo '<img src="img/teitetsu.png">';
echo '<img src="img/taiiku_boushi_tate.png">';
echo '<img src="img/undoukai_pyramid.png">';
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

$image_count = 0; // 変数が宣言されていない場合の初期化

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
    if (!empty($row['picture'])) {
        $imagePath = 'img/' . htmlspecialchars($row['picture']);
    } else {
        $imagePath = 'img/no_img.png';
    }
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
