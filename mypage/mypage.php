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
    // echo '<form action="../login/login.php" method="post"><button type="submit">ログイン</button></form>';
}

ob_end_flush(); // 出力バッファリングを終了
?>
<div class="container">
    <?php if (isset($post_count)): ?>
        <div class="profile">
            <form action="profile_change.php" method="post">
                <input type="submit" value="編集" class="button_3">
            </form>
            <?php echo '<h2>' . $_SESSION['user']['name'] . '</h2>' ?>
            <img src="../icon_img/<?php echo $_SESSION['user']['icon']; ?>" alt="アイコン" class="iconImg"> 
            <p>bio:
                <?php 
                    if (!isset($_SESSION['user']['bio']) || empty($_SESSION['user']['bio'])) {
                        echo '記載なし';
                    } else {
                        echo $_SESSION['user']['bio']; 
                    }
                ?>
            </p>
            <p>とうこうすう: <?php echo $post_count; ?></p>
        </div>
    <?php endif; ?>
    <div class="mybutton">
    <?php
        echo '<form action="../post/post.php" method="post">';
        echo '<input type="submit" value="とうこうする" class="button_1">';
        echo '</form>';

        echo '<form action="../home.php" method="post">';
        echo '<input type="submit" value="ホームへ" class="button_2">';
        echo '</form>';
    ?>
    </div>
    <a href="#" onclick="logoutchack()">ログアウト</a>
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
        echo '<h2>とうこうりれき</h2>';
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
<?php require '../top/footer.php'; ?>
