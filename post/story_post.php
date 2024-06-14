<?php session_start(); ?>
<?php require '../top/db-connect.php'; ?> 
<?php require '../top/header.php'; ?> 

<script src="../js/post.js"></script>
<link rel="stylesheet" href="../css/post.css">

<!-- 戻るボタン -->
<div class="return" id="button_1">
    <button type="submit" onclick="location.href='../mypage/mypage.php'" class="return_button" onmouseover="changeText(this, true);" onmouseout="changeText(this, false);">戻る</button>
</div>

<!-- ストーリー投稿 -->
<h1>ストーリー投稿</h1>
<form action="story_post_ok.php" method="POST" enctype="multipart/form-data">
    <label class="torokupic"><p>●ファイルを選択する●</p>
            <input type="file" name="pic" id="torokupic" accept="image/*" style="display: none;">
 
            <figure id="figure" style="display: none">
                <!-- 画像ファイルのプレビュー -->
                <figcaption></figcaption>
                <img src="" alt="" id="figureImage" width="auto" height="300px">  
            </figure>
        </label>
        <!-- コメント入力 -->
        <p>コメント</p>
        <textarea rows="3" cols="30" name="comment" id="comment"></textarea>
    <br>
    <input type="hidden" name="timestamp" id="timestamp" value="<?php echo time(); ?>">
    <button type="submit" id="post_button" class="post_button" onmouseout="changeText(this, false);" onmouseover="changeText(this, true);">投稿</button>
</form>
<script src="../js/preview.js"></script>

<?php require '../top/footer.php'; ?>
