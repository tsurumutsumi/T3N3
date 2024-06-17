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
} else {
    echo 'ユーザーがログインしていません';
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
                <?php echo '<p class="user_name">' . $_SESSION['user']['name'] . '</p>' ?>
            </div>
            <div class="head_3">
                <form action="../home.php" method="post">
                    <button type="submit" class="home_button" data-hover="▶">HOME</button>
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
            <!-- 追加: フォロワーのプルダウンメニュー -->
            <div class="head_8">
                <form action="custom_mypage.php" method="get">
                    <select name="user_id">
                        <option value="" selected disabled>フォロワーのマイページを選択</option>
                        <?php foreach ($follower_list as $follower): ?>
                            <option value="<?php echo htmlspecialchars($follower['user_id']); ?>"><?php echo htmlspecialchars($follower['user_name']); ?></option>
                        <?php endforeach; ?>
                    </select>
                    <button type="submit" class="follower_button" data-hover="▶">GO</button>
                </form>
            </div>
            <!-- 追加: フォローしている人のプルダウンメニュー -->
            <div class="head_9">
                <form action="custom_mypage.php" method="get">
                    <select name="user_id">
                        <option value="" selected disabled>フォローしている人のマイページを選択</option>
                        <?php foreach ($following_list as $following): ?>
                            <option value="<?php echo htmlspecialchars($following['user_id']); ?>"><?php echo htmlspecialchars($following['user_name']); ?></option>
                        <?php endforeach; ?>
                    </select>
                    <button type="submit" class="following_button" data-hover="▶">GO</button>
                </form>
            </div>
        </div>
        <!-- 以下省略 -->
