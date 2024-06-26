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

    // ユーザーの投稿履歴といいね数を取得する
    $stmt = $pdo->prepare("
        SELECT ph.*, 
               (SELECT COUNT(*) FROM likes WHERE likes.post_id = ph.post_id) AS like_count
        FROM post_history ph
        WHERE ph.user_id = ?
    ");
    $stmt->execute([$user_id]);
    $posts = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // フォローしている人数（フォロー数）を取得
    $stmt = $pdo->prepare("SELECT COUNT(*) AS follow_count FROM follow WHERE follower_id = ?");
    $stmt->execute([$user_id]);
    $follow_count = $stmt->fetch(PDO::FETCH_ASSOC)['follow_count'];

    // フォロワーの人数（フォロワー数）を取得
    $stmt = $pdo->prepare("SELECT COUNT(*) AS follower_count FROM follow WHERE following_id = ?");
    $stmt->execute([$user_id]);
    $follower_count = $stmt->fetch(PDO::FETCH_ASSOC)['follower_count'];

    // フォローしている人のリストを取得
    $stmt = $pdo->prepare("SELECT um.user_id, um.user_name FROM follow f JOIN user_management um ON f.following_id = um.user_id WHERE f.follower_id = ?");
    $stmt->execute([$user_id]);
    $following_list = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // フォロワーのリストを取得
    $stmt = $pdo->prepare("SELECT um.user_id, um.user_name FROM follow f JOIN user_management um ON f.follower_id = um.user_id WHERE f.following_id = ?");
    $stmt->execute([$user_id]);
    $follower_list = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
else {
    echo 'ホーム画面に戻り、ログインしてください';
    echo '<form action="../home.php" method="post">';
    echo '<button type="submit" class="home_button" data-hover="▶">HOME</button>';
    echo '</form>';
    ob_end_flush(); // 出力バッファリングを終了
    exit();
}
ob_end_flush(); // 出力バッファリングを終了
?>
<div class="container">
    <?php if (isset($post_count)): ?>
        <div class="head">
            <div class="head_1">
                <?php
                    $file_info = pathinfo($_SESSION['user']['icon']);
                    $file_name = $file_info['filename'];
                    echo '<img src="../icon_img/', htmlspecialchars($file_name), '_flame.png" alt="アイコン" class="iconImg">';
                ?>
            </div>
            <div class="head_2">
                <?php echo '<p class="user_name">' . $_SESSION['user']['name'] . '</p>' ?>
            </div>
            <div class="head_3">
                <form action="../home.php" method="post">
                    <button type="submit" class="home_button" data-hover="▶">HOME</button>
                </form>
            </div>
            <div class="head_4">
                <form action="../talk/chathome.php" method="post">
                    <button type="submit" class="talk_button" data-hover="▶">TALK</button>
                </form>
            </div>
            <div class="head_4">
                <form action="profile_change.php" method="post">
                    <button type="submit" class="update_button" data-hover="▶">UPDATE</button>
                </form>
            </div>
            <div class="head_5">
                <form action="../post/post.php" method="post">
                    <button type="submit" class="post_button" data-hover="▶">NEW POST</button>
                </form>
            </div>
            <div class="head_6">
                <form action="../post/story_post.php" method="post">
                    <button type="submit" class="post_button" data-hover="▶">NEW STORY</button>
                </form>
            </div>
            <div class="head_7">
                <form action="likes_list.php" method="post">
                    <button type="submit" class="good_button" data-hover="▶">GOOD</button>
                </form>
            </div>
        </div>
        <?php
        // ユーザーがフォローしている人数（フォロー数）を取得
        $stmt = $pdo->prepare("SELECT COUNT(*) AS follow_count FROM follow WHERE follower_id = ?");
        $stmt->execute([$user_id]);
        $follow_count = $stmt->fetch(PDO::FETCH_ASSOC)['follow_count'];

        // ユーザーをフォローしている人数（フォロワー数）を取得
        $stmt = $pdo->prepare("SELECT COUNT(*) AS follower_count FROM follow WHERE following_id = ?");
        $stmt->execute([$user_id]);
        $follower_count = $stmt->fetch(PDO::FETCH_ASSOC)['follower_count'];

        // フォローしているユーザーのリストを取得
        $stmt = $pdo->prepare("SELECT um.user_id, um.user_name FROM follow f JOIN user_management um ON f.following_id = um.user_id WHERE f.follower_id = ?");
        $stmt->execute([$user_id]);
        $following_list = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // フォロワーのユーザーのリストを取得
        $stmt = $pdo->prepare("SELECT um.user_id, um.user_name FROM follow f JOIN user_management um ON f.follower_id = um.user_id WHERE f.following_id = ?");
        $stmt->execute([$user_id]);
        $follower_list = $stmt->fetchAll(PDO::FETCH_ASSOC);
        ?>
        <div class="profile">
            <?php 
                if (!isset($_SESSION['user']['bio']) || empty($_SESSION['user']['bio'])) {
                    echo '<div class="text">bio：<span class="value">NONE</span></div>';
                } else {
                    echo '<div class="text">bio：<span class="value">'.$_SESSION['user']['bio'].'</span></div>'; 
                }
            ?>
            <div class="text">POST：<span class="value"><?php echo $post_count; ?></span></div>
            <details class="follow">
                <summary>FOLLOW：<span class="value"><?php echo $follow_count; ?></span></summary>
                <ul>
                    <?php foreach ($following_list as $following): ?>
                        <li><a href="custom_mypage.php?user_id=<?php echo htmlspecialchars($following['user_id']); ?>"><?php echo htmlspecialchars($following['user_name']); ?></a></li>
                    <?php endforeach; ?>
                </ul>
            </details>
            <details class="follower">
                <summary>FOLLOWER：<span class="value"><?php echo $follower_count; ?></span></summary>
                <ul>
                    <?php foreach ($follower_list as $follower): ?>
                        <li><a href="custom_mypage.php?user_id=<?php echo htmlspecialchars($follower['user_id']); ?>"><?php echo htmlspecialchars($follower['user_name']); ?></a></li>
                    <?php endforeach; ?>
                </ul>
            </details>
        </div>
    <?php endif; ?>
    <script>
        function logoutchack() {
            if (confirm("ログアウトしますか？") ) {
                window.location.href = "https://aso2201161.vivian.jp/T3N3/logout/logout_output.php";
            }
        }
        function deletePost(postId) {
        if (confirm("本当にこの投稿を削除しますか？")) {
            console.log("Deleting post ID: " + postId);

            var xhr = new XMLHttpRequest();
            xhr.open("POST", "../post/post_delete_ajax.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4) {
                    if (xhr.status === 200) {
                        try {
                            var response = JSON.parse(xhr.responseText);
                            if (response.success) {
                                alert("投稿が削除されました。");
                                document.getElementById('post-' + postId).remove();
                            } else {
                                alert("投稿の削除に失敗しました。");
                            }
                        } catch (e) {
                            alert("サーバーのレスポンスが不正です。");
                        }
                    } else {
                        alert("サーバーとの通信に失敗しました。");
                    }
                }
            };

            xhr.send("post_id=" + postId);
        }
        }
    </script>
    <?php if (isset($posts)): ?>
        <div class="history_title">
            <p>POST HISTORY...</p>
        </div>
        <ul class="post_list">
            <?php foreach ($posts as $post): ?>
                <li class="post" id="post-<?php echo $post['post_id']; ?>">
                    <div class="post-2">
                        <div class="post-3">
                            <?php if (isset($post['post_date'])): ?>
                                <p><?php echo $post['post_date']; ?></p>
                            <?php endif; ?>
                            <?php if (isset($post['picture'])): ?>
                                <img src="../img/<?php echo $post['picture']; ?>" alt="投稿画像" class="post_img">
                            <?php endif; ?>
                            <?php if (isset($post['comment'])): ?>
                                <p class="post_comment"><?php echo $post['comment']; ?></p>
                            <?php endif; ?>
                            <p class="like_count"><img src="../img/mark_heart_red.png"><?php echo $post['like_count']; ?>
                            <button onclick="deletePost(<?php echo $post['post_id']; ?>)" class="post_delete"><img src="../img/mark_batsu.png"></button></p>
                        </div>
                    </div>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
</div>
<?php if(isset($_SESSION['user']['id'])): ?>
    <div class="logout">
        <a href="#" onclick="logoutchack()" class="logout_link">LOGOUT</a>
    </div>
    <div class="top">
        <a href="#"><img src="../img/yajirushi_top.png" alt="TOP"></a>
    </div>
<?php endif; ?>
<?php require '../top/footer.php'; ?>
