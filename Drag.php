<?php require 'header.php';?>
<link rel="stylesheet" href="./css/Drag.css">
    <form action="" method="POST" enctype="multipart/form-data">
        <div class="upload-area">
        <img  class="imgDrag" src="img/img.png">
            <input type="file" name="upload_file" id="input-files">
        </div>
        <input type="submit" id="submit-btn" value="プレビュー表示">
    </form>

<?php require 'footer.php';?>