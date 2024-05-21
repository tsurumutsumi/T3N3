<?php
$user_id = $_POST['user_id'];
  $current_user_id = $_POST['current_user_id'];

    if(check_follow($current_user_id,$user_id)){
      $action = '解除';
      $flash_type = 'error';
      $sql ="DELETE
              FROM relation
              WHERE :follow_id = follow_id AND :follower_id = follower_id";
    }else{
      $action = '登録';
      $flash_type = 'sucsess';
      $sql ="INSERT INTO relation(follow_id,follower_id)
              VALUES(:follow_id,:follower_id)";
    }
    try {
      $dsn='mysql:dbname=shop;host=localhost;charset=utf8';
      $user='root';
      $password='';
      $dbh=new PDO($dsn,$user,$password);
      $stmt = $dbh->prepare($sql);
      $stmt->execute(array(':follow_id' => $current_user_id , ':follower_id' => $user_id));
      $return = array('action' => $action,
      'follow_count' => current(get_user_count('follow',$current_user_id)),
      'follower_count' => current(get_user_count('follower',$current_user_id)));
      echo json_encode($return);       
    }    
    catch (\Exception $e) {
      error_log('エラー発生:' . $e->getMessage());
      set_flash('error',ERR_MSG1);
      echo json_encode("error");
    }
    ?>