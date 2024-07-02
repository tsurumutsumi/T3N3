<?php
session_start();
require '../top/db-connect.php';
require '../top/header.php';
echo '<link rel="stylesheet" href="../css/account.css">';


if(isset($_POST['mail']) && isset($_POST['password'])) {
    // フォームから送信されたメールアドレスとパスワードを取得します
    $mail = $_POST['mail'];
    $password = $_POST['password'];

    // セッションにメールアドレスとパスワードを登録します
    $_SESSION['register'] = [
        'mail' => $mail,
        'password' => $password
    ];
}

?>
<!-- 追加(5/17) -->
<p class="title">SPT</p>
<!-- アカウント作成後はログインへ飛ばす -->
<div id="app" class="back">
<form id="uploadForm" action="create_account_ok.php" method="post" enctype="multipart/form-data">
    <h1>アカウント作成</h1>
    <div class="ipid">
        <input type="text" name="id" placeholder="" class="id" v-model="user_id"/>
        <p v-if="isInvalidUserId" class="error">ユーザーIDは英数字で1文字以上15文字以内で入力してください</p>
        <label>ユーザーID</label>
        <span class="focus_line"></span>
    </div>

    <div class="ipname">
        <input type="text" name="nickname" placeholder="" class="name" v-model="user_name"/>
        <p v-if="isInvalidUserName" class="error">ユーザー名は英数字と日本語で1文字以上10文字以内で入力してください</p>
        <label>ニックネーム</label>
        <span class="focus_line"></span>
    </div>
    <h1>プロフィールアイコンを選択</h1>
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

        <br>
    <script src="../js/preview.js"></script>
    <script src="../js/Drag.js"></script>
    <br><br>
    <button type="submit" id="submit-btn" class="create_button" data-hover="▶" :disabled="isInvalidUserId || isInvalidUserName">作成</button>
</div>
</form>
<script src="https://cdn.jsdelivr.net/npm/vue@2.6.14/dist/vue.min.js"></script>
<script>
    new Vue({
        el: '#app',
        data() {
            return {
                user_id: '',
                user_name: ''
            };
        },
        computed: {
            isInvalidUserId() {
                // ユーザーIDが英数字で15文字以内の場合にtrueを返す
                return !/^[a-zA-Z0-9]{1,15}$/.test(this.user_id);
            },
            isInvalidUserName() {
                // ユーザー名が英数字と日本語で10文字以内の場合にtrueを返す
                return !/^[a-zA-Z0-9ぁ-んァ-ヶー一-龠々]{1,10}$/.test(this.user_name);
            }
        }
    });
</script>

<?php require '../top/footer.php';?>
