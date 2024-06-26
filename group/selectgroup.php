<?php
session_start();
require '../top/db-connect.php';

// ユーザー一覧を取得
$dbh = new PDO($connect, USER, PASS);
$stmt = $dbh->prepare("SELECT * FROM user_management WHERE user_id != ?");
$stmt->execute([$_SESSION['user']['id']]);
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<body>
<h2>グループ作成</h2>
<form action="creategroup.php" method="POST">
    <label for="group_name">グループ名:</label>
    <input type="text" id="group_name" name="group_name" required>
    
    <h3>Invite Users:</h3>
    <?php foreach ($users as $user): ?>
        <input type="checkbox" name="user_ids[]" value="<?php echo htmlspecialchars($user['user_id'], ENT_QUOTES, 'UTF-8'); ?>">
        <?php echo htmlspecialchars($user['user_name'], ENT_QUOTES, 'UTF-8'); ?><br>
    <?php endforeach; ?>

    <input type="submit" value="Create Group">
</form>
</body>