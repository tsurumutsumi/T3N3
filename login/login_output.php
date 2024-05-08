<?php
session_start();
require '../top/db-connect.php';
require '../top/header.php';

if(isset($_POST['mail']) && isset($_POST['password'])) {
    $pdo = new PDO($connect, USER, PASS);
    $sql = $pdo->prepare('SELECT * FROM user_management WHERE mail=?');
    $sql->execute([$_POST['mail']]);
    
    if($sql->rowCount() > 0) {
        $row = $sql->fetch(PDO::FETCH_ASSOC);
        if(password_verify($_POST['password'], $row['password'])) {
            $_SESSION['user'] = [
                'id' => $row['user_id'],
                'name' => $row['user_name'],
                'pass' => $row['password'],
                'mail' => $row['mail'],
                'icon' => $row['icon']
            ];
            // ログインできたらとりあえずlogin.phpに飛ばす
            header("Location: login.php");
            exit;
        } else {
            echo 'パスワードが違います。';
        }
    } else {
        echo 'メールアドレスが見つかりません。';
    }
    
}

require '../top/footer.php';
?>
