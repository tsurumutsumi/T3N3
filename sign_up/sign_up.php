<?php
// session_start();
require '../top/db-connect.php';
require '../top/header.php';?>
<link rel="stylesheet" href="../css/sign.css">

<!-- 追加(5/17) -->
<p class="title">SPT</p>
<!-- 仮登録 -->
<div class="back">
    <form action="sign_up_ok.php" method="post">
        <h1>仮登録</h1>
        <p>メールに書かれているリンクから登録お願いします。</p>
        <div class="link">
            <a href="https://aso2201161.vivian.jp/T3N3/sign_up/sign_up_ok.php">https://aso2201161.vivian.jp/T3N3/sign_up/sign_up_ok.php</a>
        </div>
    </form>
</div>
<?php require '../top/footer.php';?>