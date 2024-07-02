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
        SELECT c.user_id, c.my_id, c.text, c.date
        FROM chat c
        JOIN (
            SELECT IF(user_id = :user_id, my_id, user_id) AS chat_partner_id, MAX(date) AS latest_date
            FROM chat
            WHERE user_id = :user_id OR my_id = :user_id
            GROUP BY chat_partner_id
        ) t2 ON (c.user_id = t2.chat_partner_id OR c.my_id = t2.chat_partner_id) AND c.date = t2.latest_date
        WHERE (c.user_id = :user_id OR c.my_id = :user_id) AND (c.user_id != :user_id OR c.my_id != :user_id)
        ORDER BY c.date DESC";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_STR);
    $stmt->execute();
    $individual_chats = $stmt->fetchAll(PDO::FETCH_ASSOC);
    //var_dump($individual_chats);
    
    // グルチャの履歴
    $g_sql = "
        SELECT gm.group_id, g.group_name as group_name, gm.user_id as user_id,
            u.user_name as user_name, gm.message as text, gm.timestamp as date
        FROM group_messages gm
        JOIN (
            SELECT group_id, MAX(timestamp) as latest_timestamp
            FROM group_messages
            GROUP BY group_id
        ) latest_gm ON gm.group_id = latest_gm.group_id AND gm.timestamp = latest_gm.latest_timestamp
        JOIN group_chat g ON gm.group_id = g.id
        JOIN user_management u ON gm.user_id = u.user_id
        JOIN group_members gmemb ON g.id = gmemb.group_id
        WHERE gmemb.user_id = :user_id
        ORDER BY gm.timestamp DESC";

    // 06/28 自分が話してないとグルチャの履歴が出ない状態
    $group_chats = $conn->prepare($g_sql);
    $group_chats->bindParam(':user_id', $user_id, PDO::PARAM_STR);
    $group_chats->execute();
    $g_individual_chats = $group_chats->fetchAll(PDO::FETCH_ASSOC);
    //var_dump($g_individual_chats);
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
    .personchat {
        display: flex;
        padding: 10px;
        border-bottom: 1px solid #eee;
    }
    .groupchat {
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
    .chat-partner {
        color: blue;
        font-size: 0.9em;
    }
    .group-name {
        color: blue;
        font-size: 0.9em;
    }
</style>
<div class="chat-container">
    <!-- 個チャの出力 -->
    <?php if (!empty($individual_chats)): ?>
        <?php foreach ($individual_chats as $chat): ?>
            <div class="personchat" data-user-id="<?php echo htmlspecialchars($chat['user_id'] == $user_id ? $chat['my_id'] : $chat['user_id']); ?>">
                <div class="chat-details">
                    <!-- ログインユーザーとのチャット相手 -->
                    <div class="chat-partner">
                        <?php 
                        if ($chat['user_id'] == $user_id) {
                            echo htmlspecialchars($chat['my_id']);
                        } else {
                            echo htmlspecialchars($chat['user_id']);
                        }
                        ?>
                    </div>
                    <!-- 最新の履歴 -->
                    <div class="chat-name">
                        <?php echo htmlspecialchars($chat['my_id']); ?>
                    </div>
                    <div class="chat-message"><?php echo htmlspecialchars($chat['text']); ?></div>
                    <div class="chat-timestamp"><?php echo htmlspecialchars($chat['date']); ?></div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>No chat history available.</p>
    <?php endif; ?>
</div>
    <!-- グルチャの出力 -->
<div class="chat-container">
    <?php if (!empty($g_individual_chats)): ?>
        <?php foreach ($g_individual_chats as $chat): ?>
            <div class="groupchat" data-group-id="<?php echo htmlspecialchars($chat['group_id']); ?>">
                <div class="chat-details">
                    <?php if (isset($chat['group_name'])): ?>
                        <div class="group-name"><?php echo htmlspecialchars($chat['group_name']); ?></div>
                    <?php endif; ?>
                    <div class="chat-name"><?php echo htmlspecialchars($chat['user_name'] ?? ''); ?></div>
                    <div class="chat-message"><?php echo htmlspecialchars($chat['text']); ?></div>
                    <div class="chat-timestamp"><?php echo htmlspecialchars($chat['date']); ?></div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>No chat history available.</p>
    <?php endif; ?>
</div>

