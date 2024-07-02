<?php 
    session_start();
    require '../top/header.php';
    require '../top/db-connect.php'; 
?>
<link rel="stylesheet" href="../css/login.css">
<script src="../js/login.js"></script>

<div class="log_button">
    <!-- 戻るボタン -->
    <div class="return" id="button_1">
        <button onclick="location.href='../home.php'" class="return_button" onmouseover="changeText(this, true);" onmouseout="changeText(this, false);">戻る</button>
    </div>
    <!-- 新規登録ボタン -->
    <div class="sign" id="button_2">
        <button type="submit" onclick="location.href='../sign_up/tentative_register.php'" class="sign_button" onmouseover="changeText(this, true);" onmouseout="changeText(this, false);">新規登録</button>
    </div>
</div>
<p class="title">SPT</p><!--追加(5/17) -->
<!-- ログイン -->
<?php
    if(isset($_SESSION['user']['id'])){
        echo '既に',$_SESSION['user']['name'],'としてログインしています';
        echo '<form action="../home.php" method="post" >';
            echo '<input type="submit" value="ホームへ">';
        echo '</form>';
        echo '<form action="../mypage/mypage.php" method="post" >';
            echo '<input type="submit" value="マイページへ">';
        echo '</form>';
    }
    else{
?>
<div id="app" class="back">
    <form action="login_output.php" method="post" >
        <h1>ログイン</h1>
        <div class="iptxt">
            <input type="text" name="mail" placeholder="" class="ml" v-model="mail"/>
            <p v-if="isInvalidMail" class="error">メールアドレスを入力してください</p>
            <label>メールアドレス</label>
            <span class="focus_line"></span>
        </div>
        <div class="ippass">
            <input type="password" name="password" placeholder="" class="pw" v-model="pass"/>
            <p v-if="isInvalidPassword" class="error">パスワードを入力してください</p>
            <!-- 変更(5/17) -->
            <label>パスワード</label>
            <span class="focus_line"></span>
        </div>
        <button type="submit" class="login_button" data-hover="▶" :disabled="isInvalidMail || isInvalidPassword">ログイン</button>
    </form>
</div>
<?php } ?>
<script src="https://cdn.jsdelivr.net/npm/vue@2.6.14/dist/vue.min.js"></script>
<script>
    new Vue({
        el: '#app',
        data() {
            return {
                mail: '',
                pass: ''
            };
        },
        computed: {
            isInvalidMail() {
                // メールアドレスが1文字未満の場合にtrueを返す
                return !/^[a-zA-Z0-9]+$/.test(this.mail) || this.mail.length < 1;
            },
            isInvalidPassword() {
                // パスワードが1文字未満の場合にtrueを返す
                return !/^[a-zA-Z0-9]+$/.test(this.pass) || this.pass.length < 1;
            }
        }
    });
</script>
<?php require '../top/footer.php'; ?>