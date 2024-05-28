<?php
session_start();
require '../top/db-connect.php';
require '../top/header.php';

    $pdo=new PDO($connect, USER, PASS);

    $sql=$pdo->prepare('select * from user_management where user_id=?');
    
    if ($sql->execute([$_SESSION['user']['id']])) {
        echo '<form action="profile_change_ok.php" method="post">';
    
        // echo 'アイコン<input type="file" name="icon" value="',$_SESSION['user']['icon'], '">'; 
        ?>
        <label class="torokupic">ファイルを選択する
        <i class="fas fa-cloud-upload-alt"></i>
        <p>Drag and drop a file or click</p>
        <input type="file" name="icon" id="torokupic" value="<?php echo $_SESSION['user']['icon']; ?>" accept="image/*" style="display: none;">
    
        <figure id="figure" style="display: none">
            <!-- 画像ファイルのプレビュー -->
            <figcaption></figcaption>
            <img src="" alt="" id="figureImage" width="300px" height="300px">  
        </figure>
        </label>
        <script src="../js/preview.js"></script>
        <?php
        echo 'ユーザーID<input type="text" name="id" value="', $_SESSION['user']['id'], '">';
        echo 'ユーザー名<input type="text" name="name" value="', $_SESSION['user']['name'], '">';
        echo 'メールアドレス<input type="text" name="mail" value="', $_SESSION['user']['mail'], '">';
    
        $bio = isset($_SESSION['user']['bio']) && !empty($_SESSION['user']['bio']) ? $_SESSION['user']['bio'] : '';
        echo 'bio<input type="text" name="bio" value="', $bio, '">';
        
        echo '新しいパスワード<input type="password" name="pass"><br>';
        echo '<br><input type="submit" value="こうしん">';
    
        echo '</form>';
    } else {

    }
    

    
?>