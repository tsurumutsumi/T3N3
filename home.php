<?php require 'top/db_connect.php'; ?>
<?php require 'top/header.php'; ?>

<div class="slideshow">
        <!-- imagesフォルダ内にある画像を表示  -->
        <!-- ここに4行追加 -->
            <img src="img/kikou.png">
            <img src="img/teitetsu.png">
            <img src="img/taiiku_boushi_tate.png">
            <img src="img/undoukai_pyramid.png">
    </div>
    <script src="script/jquery-3.7.0.min.js"></script>
    <!-- スライドショーで使うプラグイン「slick」のJavaScriptを読み込む -->
    <script src="slick/slick.min.js"></script>
    <script src="script/slideshow.js"></script>
</div>


<?php require 'top/footer.php'; ?>

