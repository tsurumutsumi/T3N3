<?php session_start(); ?>
<?php require '../top/db-connect.php'; ?> 
<?php require '../top/header.php'; ?> 

<script src="../js/post.js"></script>
<script src="../js/post_comment.js"></script>
<link rel="stylesheet" href="../css/post.css">

<!-- 追加(5/22) -->
<!-- 戻るボタン -->
<div class="return" id="button_1">
    <button type="submit" onclick="location.href='../mypage/mypage.php'" class="return_button" onmouseover="changeText(this, true);" onmouseout="changeText(this, false);">戻る</button>
</div>

<!-- 追加(5/21) -->
<h1>投稿</h1>
<form action="post_ok.php" method="POST" enctype="multipart/form-data">
    <label class="torokupic"><p>●ファイルを選択する●</p>
            <input type="file" name="pic" id="torokupic" accept="image/*" style="display: none;">
 
            <figure id="figure" style="display: none">
                <!-- 画像ファイルのプレビュー -->
                <figcaption></figcaption>
                <img src="" alt="" id="figureImage" width="auto" height="300px">  
            </figure>
        </label>
        <!-- 場所移動＋変更(5/20) -->
        <p>コメント</p>
        <div class="TextareaWrapper">
            <textarea rows="3" cols="30" name="comment" id="textarea" maxlength="30" onkeyup="ShowLength(this.value);"></textarea>
             <p id="inputlength">0文字</p>
        </div>    
        <br>
    <button type="submit" id="post_button" class="post_button" onmouseout="changeText(this, false);" onmouseover="changeText(this, true);">投稿</button>
</form>
<script src="../js/preview.js"></script>

<?php require '../top/footer.php'; ?>
