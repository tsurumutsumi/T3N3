<?php
session_start();
require '../top/db-connect.php';
require '../top/header.php';

// ユーザー一覧を取得
$dbh = new PDO($connect, USER, PASS);
$stmt = $dbh->prepare("SELECT * FROM user_management WHERE user_id != ?");
$stmt->execute([$_SESSION['user']['id']]);
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<body>
<link rel="stylesheet" href="../css/new_group.css">
<div class="head_4">
    <form action="../mypage/mypage.php" method="post">
        <button type="submit" class="home_button" data-hover="▶">BACK</button>
    </form>
</div>
<h2 class="title">グループ作成</h2>
<form action="creategroup.php" method="POST">
    <label for="group_name" class="g_name" >グループ名:</label>
    <input type="text" id="group_name" name="group_name" class="group_name" required>
    
    <h3>招待するユーザー:</h3>
    <div class="user-list">
        <?php foreach ($users as $user): ?>
            <div class="user-item">
                <input type="checkbox" name="user_ids[]" value="<?php echo htmlspecialchars($user['user_id'], ENT_QUOTES, 'UTF-8'); ?>">
                <?php echo htmlspecialchars($user['user_name'], ENT_QUOTES, 'UTF-8'); ?><br>
            </div>
        <?php endforeach; ?>
    </div>
    <input type="submit" value="Create Group">
    </div>
</form>
</body> 
