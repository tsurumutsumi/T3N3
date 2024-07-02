<?php
session_start();
require '../top/db-connect.php';
require '../top/header.php';
?>
<link rel="stylesheet" href="../css/sign.css">

<!-- 追加(5/17) -->
<p class="title">SPT</p>
<!-- アカウント作成後はログインへ飛ばす -->
<!-- 仮登録 -->
<div id="app" class="back">
    <form action="create_account.php" method="post" id="signupForm">
        <h1>アカウント作成</h1>
        <div class="iptxt">
            <input type="text" name="mail" placeholder="" class="ml" v-model="mail"/>
            <p v-if="isInvalidMail" class="error">メールアドレスは半角英数字で5文字以上で入力してください</p>
            <label>メールアドレス</label>
            <span class="focus_line"></span>
        </div>
        <div class="ippass">
            <input type="password" name="password" placeholder="" class="pw" v-model="pass"/>
            <p v-if="isInvalidPassword" class="error">パスワードは半角英数字で5文字以上で入力してください</p>
            <!-- 変更(5/17) -->
            <label>パスワード</label>
            <span class="focus_line"></span>
        </div>
        <button type="submit" class="create_button" data-hover="▶" :disabled="isInvalidPassword">作成</button>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/vue@2.6.14/dist/vue.min.js"></script>
<script>
    new Vue({
        el: '#app',
        data() {
            return {
                pass: '',
                mail: ''
            };
        },
        computed: {
            isInvalidPassword() {
                // パスワードが半角英数字で5文字未満の場合にtrueを返す
                return !/^[a-zA-Z0-9]+$/.test(this.pass) || this.pass.length < 5;
            },
            isInvalidMail() {
                // パスワードが半角英数字で5文字未満の場合にtrueを返す
                return !/^[a-zA-Z0-9]+$/.test(this.mail) || this.mail.length < 5;
            }
        }
    });
</script>

<?php require '../top/footer.php';?>