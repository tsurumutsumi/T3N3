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
<!-- アカウント作成後はログインへ飛ばす -->
<div class="back">
<form id="uploadForm" action="create_account_ok.php" method="post" enctype="multipart/form-data">
    <h1>アカウントさくせい</h1>
    <div class="ipid">
        <input type="text" name="id" placeholder="" class="id">
        <label>ユーザーID</label>
        <span class="focus_line"></span>
    </div>

    <div class="ipname">
        <input type="text" name="nickname" placeholder="" class="name">
        <label>ニックネーム</label>
        <span class="focus_line"></span>
    </div>

    <p class="icon">アイコンせってい</p>
    <div class="upload-wrapper">
        <div class="upload-area" id="uploadArea">
            <label class="file_select">しゃしんをえらぶ
                <i class="fas fa-cloud-upload-alt"></i>
                <p>Drag and drop a file or click</p>
                <input type="file" name="pic" id="torokupic" accept="image/*" style="display: none;">
                <figure id="figure" style="display: none">
                    <!-- 画像ファイルのプレビュー -->
                    <figcaption></figcaption>
                    <img src="" alt="" id="figureImage" width="300px" height="300px">  
                </figure>
            </label>
        </div>
    </div>
    <script src="../js/preview.js"></script>
    <script src="../js/Drag.js"></script>
    <br><br>
    <input type="submit" id="submit-btn" value="さくせい" class="button_1">
</div>
</form>

<?php require '../top/footer.php';?>
