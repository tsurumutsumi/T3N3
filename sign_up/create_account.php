<?php
session_start();
require '../top/db-connect.php';
require '../top/header.php';

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
<!-- アカウント作成後はログインへ飛ばす -->
<form action="create_account_ok.php" id="uploadForm" method="post" enctype="multipart/form-data">
    <h1>アカウントさくせい</h1>
    <p>ユーザーID</p>
    <input type="text" name="id">
    <br>
    <p>ニックネーム</p>
    <input type="text" name="nickname">
    <br>
    <p>アイコン設定</p>
    <div class="upload-wrapper">
        <div class="upload-area" id="uploadArea">
            <label class="file_select">ファイルを選択する
                <i class="fas fa-cloud-upload-alt"></i>
                <p>Drag and drop a file or click</p>
                <input type="file" name="pic" id="torokupic" accept="image/*" style="display: none;">
                <figure id="figure" style="display: none">
                    <!-- 画像ファイルのプレビュー -->
                    <figcaption>画像ファイルのプレビュー</figcaption>
                    <img src="" alt="" id="figureImage" width="300px" height="300px">  
                </figure>
            </label>
        </div>
    </div>
    <script src="../js/preview.js"></script>
    <script src="../js/Drag.js"></script>
    <br><br>
    <button type="submit" id="submit-btn" value="さくせい">さくせい</button>
    
</form>

<?php require '../top/footer.php';?>
