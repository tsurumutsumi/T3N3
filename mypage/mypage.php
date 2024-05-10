
<?php
//session_start();
require '../top/db-connect.php';
require '../top/header.php';

// ユーザー情報を取得する
if(isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    
    // ユーザーのアイコンを取得する
    $stmt = $pdo->prepare("SELECT icon FROM users WHERE user_id = ?");
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
/// else {
//     // ログインしていない場合はリダイレクト
//     // header("Location: login.php");
//     exit;
// }

$pdo= new PDO($connect,USER,PASS);
$sql=$pdo->query('select user_id,user_name,icon from user_management');

$user = 
?>

<div class="container">
       <h1>マイページ</h1>
        <div class="profile">
            <img src="<?php echo $user['icon']; ?>" alt="アイコン">
            <p>投稿数: <?php echo $post_count; ?></p>
        </div>
        <a href="https://aso2201161.vivian.jp/T3N3/post/post.php">とうこうする</a>
        <h2>投稿履歴</h2>
        <div class="posts">
            <?php foreach($posts as $post): ?>
                <div class="post">
                    <p><?php echo $post['date']; ?></p>
                    <img src="<?php echo $post['picture']; ?>" alt="投稿画像">
                    <p><?php echo $post['comment']; ?></p>
                </div>
            <?php endforeach; ?>
        </div>
</div>

<?php require '../top/footer.php';?>