<?php
// session_start();
require '../top/db-connect.php';
require '../top/header.php';?>

<script src="../js/login.js"></script>
<link rel="stylesheet" href="../css/sign.css">

<!-- 戻るボタン -->
<div class="return" id="button_1">
    <button type="submit" onclick="location.href='../login/login.php'" class="return_button" onmouseover="changeText(this, true);" onmouseout="changeText(this, false);">戻る</button>
</div>
<!-- 追加(5/17) -->
<p class="title">SPT</p>
<!-- 仮登録 -->
<div class="back" id="app">
    <form action="sign_up.php" method="post">
        <h1>仮登録</h1>
        <div class="iptxt">
            <input type="text" name="mail" placeholder="" class="ml" v-model="mail">
            <p v-if="isInvalidMail" class="error">メールアドレスを入力してください</p>
            <label>メールアドレス</label>
            <span class="focus_line"></span>
        </div>
            <button type="submit" class="send_button" data-hover="▶" :disabled="isInvalidMail">メール送信</button>
    </form>
</div>
<?php require '../top/footer.php';?>
<script src="https://cdn.jsdelivr.net/npm/vue@2.6.14/dist/vue.min.js"></script>
<script>
    new Vue({
        el: '#app',
        data() {
            return {
                mail: ''
            };
        },
        computed: {
            isInvalidMail() {
                // メールアドレスが1文字未満の場合にtrueを返す
                return !/^[a-zA-Z0-9]+$/.test(this.mail) || this.mail.length < 1;
            }
        }
    });
<?php require '../top/footer.php';?>