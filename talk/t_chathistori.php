<?php 
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
try {
    $conn = new PDO($connect, USER, PASS);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $user_name = $_SESSION['user']['name'];
    $user_id = $_SESSION['user']['id'];

    // 個チャの履歴
    $sql = "
    SELECT c.user_id, c.user_name, c.text, c.date
    FROM chat c
    JOIN (
        SELECT user_id, user_name, MAX(date) as latest_date
        FROM chat
        WHERE user_id = :user_id
        GROUP BY user_name
    ) t2 ON c.user_name = t2.user_name AND c.date = t2.latest_date
    WHERE c.user_id = :user_id
    ORDER BY c.date DESC";
    
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->execute();
    $individual_chats = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // グルチャの履歴
    $g_sql = "
    SELECT gm.group_id, g.group_name as group_name, gm.user_id as user_id, u.user_name as user_name, gm.message as text, gm.timestamp as date
    FROM group_messages gm
    JOIN (
        SELECT group_id, MAX(timestamp) as latest_timestamp
        FROM group_messages
        WHERE user_id = :user_id 
        GROUP BY group_id
    ) latest_gm ON gm.group_id = latest_gm.group_id AND gm.timestamp = latest_gm.latest_timestamp
    JOIN group_chat g ON gm.group_id = g.id
    JOIN user_management u ON gm.user_id = u.user_id
    WHERE u.user_id = :user_id
    ORDER BY gm.timestamp DESC";

    $group_chats = $conn->prepare($g_sql);
    $group_chats->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $group_chats->execute();
    $g_individual_chats = $group_chats->fetchAll(PDO::FETCH_ASSOC);

    // 両方の結果を結合してソート
    // $all_chats = array_merge($individual_chats, $g_individual_chats);
    // usort($all_chats, function($a, $b) {
    //     return strtotime($b['date']) - strtotime($a['date']);
    // });

    $conn = null;
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>
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
    .chat-timestamp {
        color: gray;
        font-size: 0.8em;
    }
    .group-name {
        color: blue;
        font-size: 0.9em;
    }
</style>
<div class="chat-container">
    <!-- こちゃの出力 -->
    <?php if (!empty($individual_chats)): ?>
        <?php foreach ($individual_chats as $chat): ?>
        <div class="chat">
            <div class="chat-details">
                <div class="chat-name"><?php echo htmlspecialchars($chat['user_name'] ?? ''); ?></div>
                <div class="chat-message"><?php echo htmlspecialchars($chat['text']); ?></div>
                <div class="chat-timestamp"><?php echo htmlspecialchars($chat['date']); ?></div>
            </div>
        </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>No chat history available.</p>
    <?php endif; ?>
    <hr>
    <!-- グルチャの出力 -->
    <?php if (!empty($g_individual_chats)): ?>
        <?php foreach ($g_individual_chats as $chat): ?>
        <div class="chat">
            <div class="chat-details">
                <div class="chat-name"><?php echo htmlspecialchars($chat['user_name'] ?? ''); ?></div>
                <?php if (isset($chat['group_name'])): ?>
                    <div class="group-name"><?php echo htmlspecialchars($chat['group_name']); ?></div>
                <?php endif; ?>
                <div class="chat-message"><?php echo htmlspecialchars($chat['text']); ?></div>
                <div class="chat-timestamp"><?php echo htmlspecialchars($chat['date']); ?></div>
            </div>
        </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>No chat history available.</p>
    <?php endif; ?>
</div>
