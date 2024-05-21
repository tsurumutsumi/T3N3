<?php
function check_follow($follow_user,$follower_user){
  $dsn='mysql:dbname=shop;host=localhost;charset=utf8';
  $user='root';
  $password='';
  $dbh=new PDO($dsn,$user,$password);
  $sql = "SELECT follow_id,follower_id
          FROM relation
          WHERE :follower_id = follower_id AND :follow_id = follow_id";
  $stmt = $dbh->prepare($sql);
  $stmt->execute(array(':follow_id' => $follow_user,
                       ':follower_id' => $follower_user));
  return  $stmt->fetch();
}
?>