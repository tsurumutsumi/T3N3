<?php
session_start();
require '../top/db-connect.php';
require '../top/header.php';

$pdo=new PDO($connect, USER, PASS);
$sql=$pdo->prepare('update user_management set user_id,user_name=?,password=?,mail=?,icon=?');
if(empty($_POST['name'])){
    echo 'ユーザー名を入力してください。';
}else if(empty($_POST['id'])){
    echo 'ユーザーIDを入力してください。';
}else if(empty($_POST['mail'])){
    echo 'メールアドレスを入力してください。';
}else if(empty($_POST['pass'])){
    echo 'パスワードを入力してください。';

}else if($sql->execute($_POST['id'],$_POST['name'],$_POST['pass'],$_POST['mail'],$_POST['icon'])){
    echo '更新に成功しました。';
}else{
    echo '更新に失敗しました。';
}
?>