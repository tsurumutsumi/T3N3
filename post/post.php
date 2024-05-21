<?php session_start(); ?>
<?php require '../top/db-connect.php'; ?> 
<?php require '../top/header.php'; ?> 
 
<!-- <label for="input">画像ファイル</label><br> -->
<form action="post_ok.php" method="POST" enctype="multipart/form-data">
    <div class="upload-area" id="uploadArea">
        <label class="torokupic">ファイルを選択する
            <i class="fas fa-cloud-upload-alt"></i>
            <p>Drag and drop a file or click</p>
            <input type="file" name="pic" id="torokupic" accept="image/*" style="display: none;">
 
            <figure id="figure" style="display: none">
                <!-- 画像ファイルのプレビュー -->
                <figcaption></figcaption>
                <img src="" alt="" id="figureImage" width="300px" height="300px">  
            </figure>
            <p>コメント</p>
            <input type="text" name="comment" id="comment">
        </label>
    </div>
    <input type="submit" id="submit-btn" value="投稿">
    
</form>
<script src="../js/preview.js"></script>
 
 
<?php require '../top/footer.php'; ?>