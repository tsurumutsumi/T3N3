<?php
function get_user($user_id){
    try {
      $dsn='mysql:dbname=shop;host=localhost;charset=utf8';
      $user='root';
      $password='';
      $dbh=new PDO($dsn,$user,$password);
      $sql = "SELECT id,name,password,profile,image
              FROM user
              WHERE id = :id AND delete_flg = 0 ";
      $stmt = $dbh->prepare($sql);
      $stmt->execute(array(':id' => $user_id));
      return $stmt->fetch();
    } catch (\Exception $e) {
      error_log('エラー発生:' . $e->getMessage());
      set_flash('error',ERR_MSG1);
    }
  }
  ?>