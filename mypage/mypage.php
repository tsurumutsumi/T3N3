<?php
session_start();
ob_start(); // 出力バッファリングを開始
require '../top/db-connect.php';
require '../top/header.php';
echo '<link rel="stylesheet" href="../css/mypage.css">';



$pdo=new PDO($connect,USER,PASS);
// ユーザー情報を取得する
if(isset($_SESSION['user']['id'])) {
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

    // 新しいクエリを追加する行
    $stmt = $pdo->prepare("SELECT post_date, picture, comment FROM post_history WHERE user_id = ?");
    $stmt->execute([$user_id]);
    $posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
else {
    // ログインしていない場合はリダイレクト
    // header("Location: ../login/login.php");
    // exit;
    echo 'ログインしてください（仮）';
    echo '<form action="../login/login.php" method="post"><button type="submit">ログイン</button></form>';
}
//var_dump($post_count);
$pdo= new PDO($connect,USER,PASS);
$sql=$pdo->query('select user_id,user_name,icon from user_management');
$user = $sql->fetch(PDO::FETCH_ASSOC);

ob_end_flush(); // 出力バッファリングを終了
?>
<div class="container">
    <h1>マイページ</h1>
    <?php if (isset($post_count)): ?>
        <div class="profile">
            <form action="profile_change.php" method="post">
                <input type="submit" value="編集">
            </form>
            <?php echo '<h2>'.$_SESSION['user']['name'].'</h2>'?>
            <img src="../icon_img/<?php echo $_SESSION['user']['icon']; ?>" alt="アイコン">
            <p>bio:
                <?php 
                    if(!isset($_SESSION['user']['bio']) || empty($_SESSION['user']['bio'])){
                        echo '記載なし';
                    }else{
                        echo $_SESSION['user']['bio']; 
                    }
                ?>
            </p>
            <p>とうこうすう: <?php echo $post_count; ?></p>
        </div>
    <?php endif; ?>
    <div class="button">
    <?php
        echo '<form action="../post/post.php" method="post">';
        echo '<input type="submit" value="とうこうする" class="post">';
        echo '</form>';

        echo '<form action="../home.php" method="post">';
        echo '<input type="submit" value="ホームへ" class="post_2">';
        echo '</form>';
    ?>
    </div>
        <a href="#" onclick="logoutchack()">ログアウト</a>
        <script>
            function logoutchack() {
                if(confirm("ログアウトしますか？") ) {
                    window.location.href = "https://aso2201161.vivian.jp/T3N3/logout/logout_output.php";
                }else {
                    // alert("");
                }
            }
        </script>
    <?php if (isset($posts)):
        echo '<h2>とうこうりれき</h2>';
        echo '<div class="posts">';
        foreach($posts as $post):
            echo '<div class="post">';
                if (isset($post['post_date'])):
                    echo '<p>',$post['post_date'],'</p>';
                endif;
                if (isset($post['picture'])):
                    echo '<img src="../post_img/' . $post['picture'] . '" alt="投稿画像">';
                endif;
                if (isset($post['comment'])):
                    echo '<p>',$post['comment'],'</p>';
                endif;
                echo '<form action="../post/post_delete.php" method="post">';
                    echo '<input type="submit" value="削除">';
                echo '</form>';
            echo '</div>';
        endforeach;
        echo '</div>';
         endif;
echo '</div>';
?>
<?php require '../top/footer.php';?>