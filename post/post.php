<?php session_start(); ?>
<?php require '../top/db-connect.php'; ?> 
<?php require '../top/header.php'; ?> 
<link rel="stylesheet" href="../css/post.css">

<!-- 追加(5/22) -->
<!-- 戻るボタン -->
<div class="return">
    <input type="submit" onclick="location.href='../mypage/mypage.php'" value="戻る" class="button_1">
</div>

<!-- 追加(5/21) -->
<h1>とうこう</h1>
<form action="post_ok.php" method="POST" enctype="multipart/form-data">
    <label class="torokupic"><p>●ファイルを選択する●</p>
            <input type="file" name="pic" id="torokupic" accept="image/*" style="display: none;">
 
            <figure id="figure" style="display: none">
                <!-- 画像ファイルのプレビュー -->
                <figcaption></figcaption>
                <img src="" alt="" id="figureImage" width="300px" height="300px">  
            </figure>
        </label>
        <!-- 場所移動＋変更(5/20) -->
        <p>コメント</p>
        <textarea rows="5" cols="40" name="comment" id="comment"></textarea>
    <br>
    <input type="submit" id="submit-btn" value="投稿" class="button">    
</form>
<script src="../js/preview.js"></script>

<?php require '../top/footer.php'; ?>
