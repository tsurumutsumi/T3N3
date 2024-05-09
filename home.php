<?php require 'top/db-connect.php'; ?>
<?php require 'top/header.php'; ?>
<link rel="stylesheet" href="./css/home.css">
<link rel="stylesheet" href="slick/slick.css">
<link rel="stylesheet" href="slick/slick-theme.css">



<!-- ログインボタン -->
<div class="login">
    <form action="rogin.php" method="post" >
        <button type="image" class="icon">
        <!-- 画像変更するならここ -->
            <img src="img/icon.png" alt="Button Image" class="iconImg">
        </button>
    </form>
</div>

<?php
$pdo=new PDO($connect,USER,PASS);

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


        echo '<!-- ↓投稿表示部分 -->';
        echo '<!-- 全ユーザーの投稿 -->';
        echo '<div class="post_list">';
            $sql=$pdo->prepare(
                'select comment,picture,post_date 
                from post_history '); //全ユーザーの投稿を表示
            $sql->execute();
            foreach($sql as $row){
                echo $row['comment'],$row['picture'],$row['post_date'];
                
            }
        echo '</div>';
?>


<?php require 'top/footer.php'; ?>

