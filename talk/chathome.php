<?php
session_start();
require '../top/db-connect.php';

// 現在のユーザーIDを取得
$user_id = $_SESSION['user']['id'];

// 最新の個人チャットパートナーを取得
try {
    $conn = new PDO($connect, USER, PASS);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $sql = "
        SELECT IF(user_id = :user_id, my_id, user_id) AS chat_partner_id
        FROM chat
        WHERE user_id = :user_id OR my_id = :user_id
        ORDER BY date DESC
        LIMIT 1";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_STR);
    $stmt->execute();
    $latest_chat_partner = $stmt->fetch(PDO::FETCH_ASSOC);
    $conn = null;
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}

$chat_partner_id = $latest_chat_partner['chat_partner_id'] ?? null;
$chat_partner_id = $_GET['user_id'] ?? $chat_partner_id;

// 最新のグループチャットを取得
try {
    $conn = new PDO($connect, USER, PASS);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $sql = "
        SELECT group_id
        FROM group_messages
        WHERE user_id = :user_id
        ORDER BY timestamp DESC
        LIMIT 1";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_STR);
    $stmt->execute();
    $latest_group_chat = $stmt->fetch(PDO::FETCH_ASSOC);
    $conn = null;
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}

$latest_group_id = $latest_group_chat['group_id'] ?? null;
$Group_id = $_GET['group_id'] ?? $latest_group_id;

?>

<link rel="stylesheet" href="../css/chathome.css">
<link rel="stylesheet" href="../slick/slick.css">
<link rel="stylesheet" href="../slick/slick-theme.css">

<div class="button">
    <div class="head_3">
        <form action="../home.php" method="post">
            <button type="submit" class="home_button" data-hover="▶">HOME</button>
        </form>
    </div>

    <div class="head_4">
        <form action="../group/selectgroup.php" method="post">
            <button type="submit" class="talk_button" data-hover="▶">NEW CHAT</button>
        </form>
    </div>
</div>

<?php require 'chathistorie.php'; ?>

<!-- 表示するトークルームのタイトル -->
<div class="talkroom" id="talkroom-title">
    <?php 
    if ($chat_partner_id) {
        echo '<div class="talkroomName">'.htmlspecialchars($chat_partner_id) . 'さんとのトークルーム</div>';
    } else {
        echo 'トークルーム';
    }
    ?>
</div>



<form onsubmit="sendChatData(); return false;">
    <form onsubmit="sendChatData(); return false;">
        <table summary="送信フォーム" class="sendForm">
            <tr>
                <td>
                    <?php
                    if (isset($_SESSION['user']['id'])) {
                        echo '<div class="userId">'.htmlspecialchars($_SESSION['user']['id'], ENT_QUOTES, 'UTF-8').'</div>';
                    } else {
                        echo '<div class="userId">ユーザーIDが指定されていません</div>';
                        exit;
                    }
                    ?>
                </td>
            </tr>
            <tr class="talk">
                <td>50字以内でチャットしてください<br><input type="text" id="text" style="width:100%" maxlength="50" required /></td>
            </tr>
        </table>
        <input type="submit" value="送信" class="send_button" />
    </form>
</form>
<input type="hidden" id="user_id" value="<?php echo htmlspecialchars($chat_partner_id, ENT_QUOTES, 'UTF-8'); ?>">
<input type="hidden" id="group_id" value="<?php echo htmlspecialchars($Group_id, ENT_QUOTES, 'UTF-8'); ?>">

<!-- 自分の名前 -->
<input type="hidden" id="my_id" value="<?php echo htmlspecialchars($_SESSION['user']['id'], ENT_QUOTES, 'UTF-8'); ?>">

<div class="contents_box">
    <table summary="チャット" class="chat">
        <tbody id="board"></tbody>
    </table>
</div>

<script type="text/javascript">
var xmlHttpObject;

function createXMLHttpRequest(){
    var xmlHttpObject = null;
    if(window.XMLHttpRequest){
        xmlHttpObject = new XMLHttpRequest();
    }else if(window.ActiveXObject){
        try{
            xmlHttpObject = new ActiveXObject("Msxml2.XMLHTTP");
        }catch(e){
            try{
                xmlHttpObject = new ActiveXObject("Microsoft.XMLHTTP");
            }catch(e){
                return null;
            }
        }
    }
    return xmlHttpObject;
}

function loadChatData(isGroup = false, id = null){
    xmlHttpObject = createXMLHttpRequest();
    xmlHttpObject.onreadystatechange = displayHtml;
    if (isGroup) {
        xmlHttpObject.open("POST", '../group/g_loadChatData.php', true);
        xmlHttpObject.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xmlHttpObject.send("groupId=" + encodeURIComponent(id));
    } else {
        xmlHttpObject.open("POST", 'loadChatData.php', true);
        xmlHttpObject.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xmlHttpObject.send("userId=" + encodeURIComponent(id));
    }
}

function displayHtml(){
    if((xmlHttpObject.readyState == 4) && (xmlHttpObject.status == 200) && xmlHttpObject.responseText){
        document.getElementById("board").innerHTML = xmlHttpObject.responseText;
    }
}

function sendChatData() {
    var text = document.getElementById("text").value;
    var groupId = document.getElementById("group_id").value;
    var userId = document.getElementById("user_id").value;
    var myId = document.getElementById("my_id").value;

    xmlHttpObject = createXMLHttpRequest();
    var url = "";

    if (groupId) {
        url = "../group/g_sendChatData.php";
        xmlHttpObject.open("POST", url, true);
        xmlHttpObject.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xmlHttpObject.onreadystatechange = function () {
            if (xmlHttpObject.readyState == 4 && xmlHttpObject.status == 200) {
                var response = JSON.parse(xmlHttpObject.responseText);
                if (response.status === 'success') {
                    loadChatData(true, groupId);
                } else {
                    alert(response.message);
                }
            }
        };
        xmlHttpObject.send(
            "groupId=" + encodeURIComponent(groupId) +
            "&myId=" + encodeURIComponent(myId) +
            "&text=" + encodeURIComponent(text)
        );
    } else {
        url = "sendChatData.php";
        xmlHttpObject.open("POST", url, true);
        xmlHttpObject.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xmlHttpObject.onreadystatechange = function () {
            if (xmlHttpObject.readyState == 4 && xmlHttpObject.status == 200) {
                var response = JSON.parse(xmlHttpObject.responseText);
                if (response.status === 'success') {
                    loadChatData(false, userId);
                } else {
                    alert(response.message);
                }
            }
        };
        xmlHttpObject.send(
            "userId=" + encodeURIComponent(userId) +
            "&myId=" + encodeURIComponent(myId) +
            "&text=" + encodeURIComponent(text)
        );
    }

    document.getElementById("text").value = "";  // フォームをクリアする
}


// 初回ロード時に個人チャットデータを取得
loadChatData(false, document.getElementById('user_id').value);

// 3秒ごとに個人チャットの内容を取りに行く
setInterval(() => {
    var isGroup = document.getElementById('user_id').value.startsWith('group-');
    var id = isGroup ? document.getElementById('group_id').value : document.getElementById('user_id').value;
    loadChatData(isGroup, id);
}, 3000);

document.querySelectorAll('.chat-container .personchat, .chat-container .groupchat').forEach(chat => {
    chat.addEventListener('click', function(event) {
        event.preventDefault();
        
        var userId = this.getAttribute('data-user-id');
        var groupId = this.getAttribute('data-group-id');
        
        if (userId) {
            console.log('User ID:', userId);
            document.getElementById('user_id').value = userId;
            document.getElementById('group_id').value = ''; // グループIDをクリア
            loadChatData(false, userId);
            // ユーザー名を取得してタイトルを更新
            updateChatTitle(userId);
        } else if (groupId) {
            console.log('Group ID:', groupId);
            document.getElementById('group_id').value = groupId;
            document.getElementById('user_id').value = groupId; // user_id にグループ識別子を設定
            loadChatData(true, groupId);
            // グループ名を取得してタイトルを更新
            updateGroupTitle(groupId);
        } else {
            console.log('見つからないよん');
        }
    });
});

function updateChatTitle(userId) {
    var xmlHttpObject = createXMLHttpRequest();
    xmlHttpObject.onreadystatechange = function() {
        if (xmlHttpObject.readyState == 4 && xmlHttpObject.status == 200) {
            var userName = xmlHttpObject.responseText;
            document.getElementById('talkroom-title').innerText = userName + 'さんとのトークルーム';
        }
    };
    xmlHttpObject.open("GET", "getUserName.php?user_id=" + encodeURIComponent(userId), true);
    xmlHttpObject.send(null);
}

function updateGroupTitle(groupId) {
    var xmlHttpObject = createXMLHttpRequest();
    xmlHttpObject.onreadystatechange = function() {
        if (xmlHttpObject.readyState == 4 && xmlHttpObject.status == 200) {
            var groupName = xmlHttpObject.responseText;
            document.getElementById('talkroom-title').innerText = groupName + 'グループのトークルーム';
        }
    };
    xmlHttpObject.open("GET", "getGroupName.php?group_id=" + encodeURIComponent(groupId), true);
    xmlHttpObject.send(null);
}

</script>
