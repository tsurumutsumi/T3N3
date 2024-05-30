<?php
session_start();
ob_start(); // 出力バッファリングを開始
require '../top/db-connect.php';
require '../top/header.php';
echo '<link rel="stylesheet" href="../css/mypage.css">';

$pdo = new PDO($connect, USER, PASS);
// ユーザー情報を取得する
if (isset($_SESSION['user']['id'])) {
    $user_id = $_SESSION['user']['id'];
    
    // ユーザーのアイコンを取得する
    $stmt = $pdo->prepare("SELECT icon FROM user_management WHERE user_id = ?");
    $stmt->execute([$user_id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // ユーザーの投稿数を取得する
    $stmt = $pdo->prepare("SELECT COUNT(*) AS post_count FROM post_history WHERE user_id = ?");
    $stmt->execute([$user_id]);
    $post_count = $stmt->fetch(PDO::FETCH_ASSOC)['post_count'];

    // ユーザーの投稿履歴を取得する
    $stmt = $pdo->prepare("SELECT * FROM post_history WHERE user_id = ?");
    $stmt->execute([$user_id]);
    $posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
else {
    echo 'ホーム画面に戻り、ログインしてください';
    echo '<form action="../home.php" method="post">';
        echo '<input type="submit" value="ホームへ" class="button_2">';
    echo '</form>';
    // echo '<form action="../login/login.php" method="post"><button type="submit">ログイン</button></form>';
}

ob_end_flush(); // 出力バッファリングを終了
?>
<div class="container">
    <?php if (isset($post_count)): ?>
        <div class="head">
            <div class="head_1">
                <img src="../icon_img/<?php echo $_SESSION['user']['icon']; ?>" alt="アイコン" class="iconImg">
            </div>
            <div clacc="head_2">
                <?php echo '<p class="user_name">' . $_SESSION['user']['name'] . '</p>' ?>
            </div>
            <div class="head_3">
                <form action="../home.php" method="post">
                    <input type="submit" value="HOME" class="head_button">
                </form>
            </div>
            <div class="head_4">
                <form action="profile_change.php" method="post">
                        <input type="submit" value="UPDATE" class="head_button">
                </form>
            </div>
            <div class="head_5">
                <form action="../post/post.php" method="post">
                    <input type="submit" value="N³EW POST" class="head_button">
                </form>
            </div>
            <!-- 形のみです -->
            <div class="head_6">
                <form action="../talk/talk.php" method="post">
                    <input type="submit" value="T³ALK" class="head_button">
                </form>
            </div>
            <!-- 形のみです(いいねした投稿を表示) -->
            <div class="head_7">
                <form action="../good/good.php" method="post">
                    <input type="submit" value="GOOD" class="head_button">
                </form>
            </div>
        </div>
        <div class="profile">
            <!-- <div class="information"> -->
                <!-- bio: -->
                    <?php 
                        if (!isset($_SESSION['user']['bio']) || empty($_SESSION['user']['bio'])) {
                            echo '<div class="text"><span>bio:</span>NONE</div>';
                        } else {
                            echo '<div class="text"><span>bio:</span>'.$_SESSION['user']['bio'].'</div>'; 
                        }
                    ?>
                <div class="text"><span>POST:</span><?php echo $post_count; ?></div>
                <div class="text"><span>FOLLOW：</span>12</div>
                <div class="text"><span>FOLLOWER：10</div>
        </div>
        <!-- </div> -->
    <?php endif; ?>
    <!-- <a href="#" onclick="logoutchack()">ログアウト</a> -->
    <script>
        function logoutchack() {
            if (confirm("ログアウトしますか？") ) {
                window.location.href = "https://aso2201161.vivian.jp/T3N3/logout/logout_output.php";
            }
        }
        function deletePost(postId) {
        if (confirm("本当にこの投稿を削除しますか？")) {
            console.log("Deleting post ID: " + postId); // デバッグログ

            var xhr = new XMLHttpRequest();
            xhr.open("POST", "../post/post_delete_ajax.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4) {
                    console.log("AJAX request completed with status: " + xhr.status); // デバッグログ
                    if (xhr.status === 200) {
                        try {
                            console.log("Response received: " + xhr.responseText); // デバッグログ
                            var response = JSON.parse(xhr.responseText);
                            if (response.success) {
                                alert("投稿が削除されました。");
                                document.getElementById('post-' + postId).remove();
                            } else {
                                alert("投稿の削除に失敗しました。");
                            }
                        } catch (e) {
                            console.error("Failed to parse JSON response: " + e); // デバッグログ
                            console.error("Response received: " + xhr.responseText); // デバッグログ
                            alert("サーバーのレスポンスが不正です。");
                        }
                    } else {
                        console.error("AJAX request failed with status: " + xhr.status); // デバッグログ
                        alert("サーバーとの通信に失敗しました。");
                    }
                }
            };

            xhr.send("post_id=" + postId);
        }
        }
    </script>
    <?php if (isset($posts)):
        echo '<div class="history_title">';
            echo '<p>POST HISTORY...</p>';
        echo '</div>';
        echo '<div class="posts">';
        foreach ($posts as $post):
            echo '<div class="post" id="post-' . $post['post_id'] . '">';
                if (isset($post['post_date'])):
                    echo '<p>', $post['post_date'], '</p>';
                endif;
                if (isset($post['picture'])):
                    echo '<img src="../post_img/' . $post['picture'] . '" alt="投稿画像">';
                endif;
                if (isset($post['comment'])):
                    echo '<p>', $post['comment'], '</p>';
                endif;
                echo '<button onclick="deletePost(' . $post['post_id'] . ')">削除</button>';
            echo '</div>';
        endforeach;
        echo '</div>';
    endif;
echo '</div>';
?>
<div class="logout">
    <a href="#" onclick="logoutchack()" class="logout_link">LOGOUT</a>
</div>
<?php require '../top/footer.php'; ?>
