<?php
session_start();
require '../top/db-connect.php';
require '../top/header.php';
echo '<link rel="stylesheet" href="../css/mypage_change.css">';
echo '<link rel="stylesheet" href="../css/account.css">';

?>
<script src="../js/profile_change.js"></script>

<!-- 戻るボタン  -->
<div class="return" id="button_1">
    <button type="submit" onclick="location.href='../mypage/mypage.php'" class="return_button" onmouseover="changeText(this, true);" onmouseout="changeText(this, false);">戻る</button>
</div>

<?php
    $pdo=new PDO($connect, USER, PASS);

    $sql=$pdo->prepare('select * from user_management where user_id=?');
    
    if ($sql->execute([$_SESSION['user']['id']])) {
        echo '<div class="back">';
            echo '<form action="profile_change_ok.php" method="post">';
        
            // echo 'アイコン<input type="file" name="icon" value="',$_SESSION['user']['icon'], '">'; 
            ?>
            <!-- <div class="icon_img">
                <label class="torokupic">ファイルを選択する
                <input type="file" name="icon" id="torokupic" value="<?php echo $_SESSION['user']['icon']; ?>" accept="image/*" style="display: none;">
            
                <figure id="figure" class="icon_encircle" style="display: none">
                    画像ファイルのプレビュー
                    <figcaption></figcaption>
                    <img src="" alt="" id="figureImage" width="300px" height="300px">  
                </figure>
                </label>
            </div> -->
            <?php
                $iconDir = '../icon_img/';
                // 出力したい画像のファイル名
                $specifiedImages = ['bunnygirl.png', 'devil.png', 'hime_child.png', 'hime.png', 'kishi.png',
                'madoshi.png', 'maid.png', 'murabito_man.png', 'murabito_woman.png', 'ningyo.png', 'oji.png', 
                'osama.png', 'shinigami.png', 'shituji.png', 'sister.png', 'skeleton.png', 'tenshi.png', 'tozoku.png', 
                'yosei.png']; // ここに出力したい画像のファイル名を指定してください
                $icons = scandir($iconDir);
                foreach ($icons as $icon) {
                    if ($icon !== '.' && $icon !== '..' && in_array($icon, $specifiedImages)) {
                        echo '<div class="icon-option">';
                        echo '<label>';
                        echo '<input type="radio" name="icon" value="' . $iconDir . $icon . '">';
                        echo '<img src="' . $iconDir . $icon . '" alt="アイコン" class="icon_img">';
                        echo '</label>';
                        echo '</div>'; 
                    }
                }
            ?>
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
                echo '<button type="submit" class="update_button" date-hover="▶更新">更新</button>';
        
            echo '</form>';
        echo '</div>';
    } else {

    }
    

    
?>