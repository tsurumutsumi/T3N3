<?php
session_start();
require '../top/db-connect.php';
require '../top/header.php';

    $pdo=new PDO($connect, USER, PASS);

    $sql=$pdo->prepare('select * from user_management where user_id=?');
    
    if($sql->execute([$_SESSION['user']['id']])){
        echo '<form action="profile_change_ok.php" method="post">';
            echo 'アイコン<input type="file" name="icon" value="',$_SESSION['user']['icon'], '">'; 
            echo 'ユーザーID<input type="text" name="id" value="',$_SESSION['user']['id'], '">';    
            echo 'ユーザー名<input type="text" name="name" value="',$_SESSION['user']['name'], '">';
            echo 'メールアドレス<input type="text" name="mail" value="',$_SESSION['user']['mail'], '">';
            echo 'パスワード<input type="password" name="pass" value="',$_SESSION['user']['pass'], '">';
            echo '<br><input type="submit" value="こうしん">';
        echo '</form>';
    }else{

    }

    
?>