<?php
session_start();
require '../top/db-connect.php';
require '../top/header.php';
echo '<link rel="stylesheet" href="../css/mypage_change.css">';
?>
<!-- 戻るボタン  -->
<div class="return">
    <input type="submit" onclick="location.href='../mypage/mypage.php'" value="戻る" class="button_1">
</div>

<?php
    $pdo=new PDO($connect, USER, PASS);

    $sql=$pdo->prepare('select * from user_management where user_id=?');
    
    if ($sql->execute([$_SESSION['user']['id']])) {
        echo '<form action="profile_change_ok.php" method="post">';
    
        // echo 'アイコン<input type="file" name="icon" value="',$_SESSION['user']['icon'], '">'; 
        ?>
        <div class="icon_img">
            <label class="torokupic">ファイルを選択する
            <input type="file" name="icon" id="torokupic" value="<?php echo $_SESSION['user']['icon']; ?>" accept="image/*" style="display: none;">
        
            <figure id="figure" style="display: none">
                <!-- 画像ファイルのプレビュー -->
                <figcaption></figcaption>
                <img src="" alt="" id="figureImage" width="300px" height="300px">  
            </figure>
            </label>
        </div>
        <script src="../js/preview.js"></script>
        <?php
            echo '<div class="ipid">';
                echo '<input type="text" name="id" class="id" value="', $_SESSION['user']['id'], '">';
                echo '<label>ユーザーID</label>';
                echo '<span class="focus_line"></span>';
            echo '</div>';

            echo '<div class="ipname">';
                echo '<input type="text" name="name" class="name" value="', $_SESSION['user']['name'], '">';
                echo '<label>ユーザー名</label>';
                echo '<span class="focus_line"></span>';
            echo '</div>';

            echo '<div class="ipmail">';
                echo '<input type="text" name="mail" class="mail" value="', $_SESSION['user']['mail'], '">';
                echo '<label>メールアドレス</label>';
                echo '<span class="focus_line"></span>';
            echo '</div>';
    
            $bio = isset($_SESSION['user']['bio']) && !empty($_SESSION['user']['bio']) ? $_SESSION['user']['bio'] : '';
            echo '<div class="ipbio">';
                echo '<input type="text" name="bio" class="bio" value="', $bio, '">';
                echo '<label>bio</label>';
                echo '<span class="focus_line"></span>';
            echo '</div>';  

            echo '<div class="ippw">';
                echo '<input type="password" name="pass" class="pass"><br>';
                echo '<label>新しいパスワード</label>';
                echo '<span class="focus_line"></span>';
            echo '</div>';
            echo '<br><input type="submit" value="こうしん" class="button">';
    
        echo '</form>';
    } else {

    }
    

    
?>