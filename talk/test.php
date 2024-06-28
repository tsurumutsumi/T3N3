<?php
session_start();
// セッションから自分のユーザー情報を取得
if (!isset($_SESSION['user']['name'])) {
    die("User not logged in.");
}
 
$user_name = $_SESSION['user']['name'];
 
$servername = "mysql303.phy.lolipop.lan";
$username = "LAA1517798";
$password = "0000";
$dbname = "LAA1517798-stp";
 
$conn = new mysqli($servername, $username, $password, $dbname);
 
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
 
// SQLクエリを変更して、自分のユーザー名に関連するチャット履歴のみを取得
$sql = "
SELECT t1.user_id, t1.user_name, t1.text, t1.date
FROM chat t1
JOIN (
    SELECT user_id, MAX(date) as latest_date
    FROM chat
    WHERE user_name = ?
    GROUP BY user_id
) t2 ON t1.user_id = t2.user_id AND t1.date = t2.latest_date
WHERE t1.user_name = ?
ORDER BY t1.date DESC";
 
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $user_name, $user_name);
$stmt->execute();
$result = $stmt->get_result();
 
$chats = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $chats[] = $row;
    }
} else {
    echo "0 results";
}
$conn->close();
?>
 
<!DOCTYPE html>
<html>
<head>
    <style>
        .chat-container {
            width: 300px;
            border: 1px solid #ccc;
        }
        .chat {
            display: flex;
            padding: 10px;
            border-bottom: 1px solid #eee;
        }
        .avatar {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            margin-right: 10px;
        }
        .chat-details {
            flex: 1;
        }
        .chat-name {
            font-weight: bold;
        }
        .chat-message {
            margin-top: 5px;
        }
    </style>
</head>
<body>
<div class="chat-container">
    <?php foreach ($chats as $chat): ?>
    <div class="chat">
        <div class="chat-details">
            <div class="chat-name"><?php echo htmlspecialchars($chat['user_id']); ?></div>
            <div class="chat-message"><?php echo htmlspecialchars($chat['text']); ?></div>
            <div class="chat-timestamp"><?php echo htmlspecialchars($chat['date']); ?></div>
        </div>
    </div>
    <?php endforeach; ?>
</div>
</body>
</html>
 