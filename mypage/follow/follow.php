<?php
//$current_user = get_user($_GET['user_id']);
$user_management = get_user($_GET['user_id']);
?>
<form action="#" method="post">
          <input type="hidden" class="current_user_id" value="<?= $current_user['id'] ?>">
          <input type="hidden" name="follow_user_id" value="<?= $user_id ?>">
          <?php if (check_follow($user_management['user_name'],$user_id)): ?>
          <button class="follow_btn border_white btn following" type="button" name="follow">フォロー中</button>
          <?php else: ?>
          <button class="follow_btn border_white btn" type="button" name="follow">フォロー</button>
          <?php endif; ?>
</form>