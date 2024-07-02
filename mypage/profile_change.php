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
        echo '<div id="app" class="back">';
            echo '<form action="profile_change_ok.php" method="post">';
            ?>
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
                        echo '<input type="radio" name="icon" value="' . $iconDir . $icon . '" v-model="selectedImage" @change="imageSelected = true">';
                        // 画像が選択されていない場合のエラーメッセージ
                        echo '<p v-if="!imageSelected" class="imgerror">画像を選択してください</p>';
                        echo '<img src="' . $iconDir . $icon . '" alt="アイコン" class="icon_img">';
                        echo '</label>';
                        echo '</div>'; 
                    }
                }
            ?>
            <script src="../js/preview.js"></script>
            <?php
                echo '<div class="ipid">';
                    echo '<input type="text" name="id" class="id" value="', $_SESSION['user']['id'], '" v-model="id">';
                    // var_dump($_SESSION['user']['id']);データが表示されるかのテスト用
                    echo '<p v-if="isInvalidUser_id" class="error">ユーザーIDを1文字以上15文字以内で入力してください</p>';
                    echo '<label>ユーザーID</label>';
                    echo '<span class="focus_line"></span>';
                echo '</div>';

                echo '<div class="ipname">';
                    echo '<input type="text" name="name" class="name" value="', $_SESSION['user']['name'], '" v-model="name">';
                    // var_dump($_SESSION['user']['name']);データが表示されるかのテスト用
                    echo '<p v-if="isInvalidUser_name" class="error">ユーザー名を1文字以上10文字以内で入力してください</p>';
                    echo '<label>ユーザー名</label>';
                    echo '<span class="focus_line"></span>';
                echo '</div>';

                echo '<div class="ipmail">';
                    echo '<input type="text" name="mail" class="mail" value="', $_SESSION['user']['mail'], '" v-model="mail">';
                    // var_dump($_SESSION['user']['mail']);データが表示されるかのテスト用
                    echo '<p v-if="isInvalidMail" class="error">メールアドレスを5文字以上で入力してください</p>';
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
                    echo '<input type="password" name="pass" class="pass" v-model="pass"><br>';
                    echo '<label>新しいパスワード</label>';
                    echo '<p v-if="isInvalidPassword" class="error">パスワードは半角英数字で5文字以上で入力してください</p>';
                    echo '<span class="focus_line"></span>';
                echo '</div>';
                echo '<button type="submit" id="update_button" class="update_button" onmouseout="changeText(this, false);" onmouseover="changeText(this, true);" :disabled="isInvalidUser_id || isInvalidUser_name || isInvalidMail || !imageSelected || isInvalidPassword">更新</button>';        
            echo '</form>';
        echo '</div>';
    } else {

    }
    

    
?>
<script src="https://cdn.jsdelivr.net/npm/vue@2.6.14/dist/vue.min.js"></script>
<script>
    new Vue({
        el: '#app',
        data() {
            return {
                id: '<?php echo $_SESSION["user"]["id"]; ?>',
                name: '<?php echo $_SESSION["user"]["name"]; ?>',
                mail: '<?php echo $_SESSION["user"]["mail"]; ?>',
                selectedImage: '',
                imageSelected: false, // 画像が選択されているかどうかのフラグ
                pass:''
            };
        },
        computed: {
            isInvalidUser_id() {
                // ユーザーIDが0文字または16文字以上の場合にtrueを返す
                return !/^[a-zA-Z0-9]+$/.test(this.id) || this.id.length < 1 || this.id.length > 15;
            },
            isInvalidUser_name() {
                // ユーザー名が0文字または11文字以上の場合にtrueを返す
                return !/^[a-zA-Z0-9]+$/.test(this.name) || this.name.length < 1 || this.name.length > 10;
            },
            isInvalidMail() {
                // メールアドレスが5文字未満の場合にtrueを返す
                return !/^[a-zA-Z0-9]+$/.test(this.mail) || this.mail.length < 5;
            },
            isInvalidPassword() {
                // パスワードが半角英数字で5文字未満の場合にtrueを返す
                return !/^[a-zA-Z0-9]+$/.test(this.pass) || this.pass.length < 5;
            }
        }
    });