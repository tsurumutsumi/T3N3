<?php 
    require header.php;
    
    echo '<fom action="" method="POST" enctype="multipart/form-data">';
        echo '<div class="upload-area">';
            echo '<i class="fas fa-cloud-upload-alt"></i>';
            echo '<p>Drag and drop a file or click</p>';
            echo '<input type="file" name="upload_file" id="input-files">';
        echo '</div>';
        echo '<input type="submit" id="submit-btn" value="送信">';
    echo '</form>';

    require footer.php;
?>