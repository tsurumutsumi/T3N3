<?php
session_start();
ob_start();
require '../top/db-connect.php';
require '../top/header.php';
echo '<link rel="stylesheet" href="../css/login.css">';

if(isset($_POST['mail']) && isset($_POST['password'])) {
    $pdo = new PDO($connect, USER, PASS);
    $sql = $pdo->prepare('SELECT * FROM user_management WHERE mail=?');
    $sql->execute([$_POST['mail']]);
    echo '<h1>ログイン</h1>';
    if($sql->rowCount() > 0) {
        $row = $sql->fetch(PDO::FETCH_ASSOC);
        // データベースから取得したハッシュ化されたパスワードと入力されたパスワードを比較する
        if(password_verify($_POST['password'], $row['password'])) {
            $_SESSION['user'] = [
                'id' => $row['user_id'],
                'name' => $row['user_name'],
                'pass' => $row['password'],
                'mail' => $row['mail'],
                'icon' => $row['icon'],
                'bio'  => $row['self_introduction']
            ];
            // ログインできたらhome.phpに飛ばす
            echo '<script>
                window.location.replace("../home.php");
            </script>';
            exit;
        } else {
            echo '<p class="text">パスコードがちがいます。</p>';
            // 戻るボタン追加
            echo '<a href="https://aso2201161.vivian.jp/T3N3/login/login.php" class="no_login"><p>もどる<p></a>';
        }
    } else {
        echo '<p class="text">メールアドレスがみつかりません。</p>';
        // 戻るボタン追加
        echo '<a href="https://aso2201161.vivian.jp/T3N3/login/login.php" class="no_login"><p>もどる</p></a>';

    }
}
ob_end_flush(); // バッファリング終了

require '../top/footer.php';
?>
