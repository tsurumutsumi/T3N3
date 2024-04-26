<?php require 'header.php';?>

    <fom action="" method="POST" enctype="multipart/form-data">
        <div class="upload-area">
            <i class="fas fa-cloud-upload-alt"></i>
            <p>Drag and drop a file or click</p>
            <input type="file" name="upload_file" id="input-files">
        </div>
        <input type="submit" id="submit-btn" value="送信">
    </form>

<?php require 'footer.php';?>