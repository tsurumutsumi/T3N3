<?php
ob_start(); // 出力バッファリングを開始
session_start();
require '../top/db-connect.php';
require '../top/header.php';
echo '<link rel="stylesheet" href="../css/mypage.css">';

$pdo = new PDO($connect, USER, PASS);

// URLパラメータからユーザーIDを取得
if (isset($_GET['user_id'])) {
    $user_id = $_GET['user_id'];
    
    // ユーザーのアイコンを取得する
    $stmt = $pdo->prepare("SELECT icon FROM user_management WHERE user_id = ?");
    $stmt->execute([$user_id]);
    $user_icon = $stmt->fetchColumn();

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
                    if (!empty($user_icon)) {
                        $file_info = pathinfo($user_icon);
                        $file_name = $file_info['filename'];
                        echo '<img src="../icon_img/', htmlspecialchars($file_name), '_flame.png" alt="アイコン" class="iconImg">';
                    } else {
                        echo '<img src="../img/no_img.png" alt="デフォルトアイコン" class="iconImg">';
                    }
                ?>
            </div>
            <div class="head_2">
                <?php echo '<p class="user_name">' . htmlspecialchars($user_id) . '</p>' ?>
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
                <form action="../talk/talk.html?user_id="$user_id method="post">
                    <button type="submit" class="talk_button" data-hover="▶">TALK</button>
                </form>
            </div>
        </div>
        <div class="profile">
        <?php 
            if (!isset($_SESSION['user']['bio']) || empty($_SESSION['user']['bio'])) {
                echo '<div class="text">bio：<span class="value">NONE</span></div>';
            } else {
                echo '<div class="text">bio：<span class="value">'.$_SESSION['user']['bio'].'</span></div>'; 
            }
        ?>
            <div class="text">POST：<span class="value"><?php echo htmlspecialchars($post_count); ?></span></div>
            <div class="text">FOLLOW：<span class="value">12</span></div>
            <div class="text">FOLLOWER：<span class="value">10</span></div>
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
                                    <p class="like_count">いいね: <?php echo htmlspecialchars($post['like_count']); ?></p>
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
<?php require '../top/footer.php'; ?>
