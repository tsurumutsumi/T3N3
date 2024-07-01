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

?>

<link rel="stylesheet" href="../css/chathome.css">
<link rel="stylesheet" href="../slick/slick.css">
<link rel="stylesheet" href="../slick/slick-theme.css">

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

<?php require 't_chathistori.php'; ?>

<!-- 表示するトークルームのタイトル -->
<?php 
if ($chat_partner_id) {
    echo htmlspecialchars($chat_partner_id) . 'さんとのトークルーム';
} else {
    echo 'トークルーム';
}
?>

<form onsubmit="sendChatData(); return false;">
    <table summary="送信フォーム">
        <tr>
            <?php 
                if (!isset($_SESSION['user']['icon']) || empty($_SESSION['user']['icon'])) {
                    echo '<td class="icon"><img src="../icon_img/icon.png" alt="アイコン" class="iconImg"></td>';
                } else {
                    $file_info = pathinfo($_SESSION['user']['icon']);
                    $file_name = $file_info['filename'];
                    echo '<td class="icon"><img src="../icon_img/', htmlspecialchars($file_name), '_flame.png" alt="アイコン" class="iconImg"></td>';
                }
            ?>
        </tr>
    </table>
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
        <input type="submit" value="送信" class="send_button" /></form>
</form>
<input type="hidden" id="user_id" value="<?php echo htmlspecialchars($chat_partner_id, ENT_QUOTES, 'UTF-8'); ?>">
<!-- 自分の名前 -->
<input type="hidden" id="my_id" value="<?php echo htmlspecialchars($_SESSION['user']['id'], ENT_QUOTES, 'UTF-8'); ?>">

<div class="contents_box">
    <table summary="チャット" class="chat">
        <tbody id="board"></tbody>
    </table>
</div>

<p>グループ一覧</p>
<ul>
<?php
// 自分が所属しているグループを取得
$dbh = new PDO($connect, USER, PASS);
$stmt = $dbh->prepare("SELECT gc.id, gc.group_name FROM group_chat gc JOIN group_members gm ON gc.id = gm.group_id WHERE gm.user_id = ?");
$stmt->execute([$user_id]);
$groups = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (count($groups) > 0) {
    foreach ($groups as $group) {
        $group_id = $group['id'] ?? '';
        $group_name = $group['group_name'] ?? '';
        echo '<li><a href="../group/groupchat.php?group_id=', htmlspecialchars($group_id ?? '', ENT_QUOTES, 'UTF-8'), '">', htmlspecialchars($group_name ?? '', ENT_QUOTES, 'UTF-8'), '</a></li>';
    }
} else {
    echo '<li>グループがありません。</li>';
}
?>
</ul>

<script type="text/javascript">
var userId = document.getElementById("user_id").value;
var myId = document.getElementById("my_id").value;
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

function loadChatData(){
    xmlHttpObject = createXMLHttpRequest();
    xmlHttpObject.onreadystatechange = displayHtml;
    xmlHttpObject.open("POST", 'loadChatData.php', true);
    xmlHttpObject.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xmlHttpObject.send("userId=" + encodeURIComponent(userId));
}

function displayHtml(){
    if((xmlHttpObject.readyState == 4) && (xmlHttpObject.status == 200) && xmlHttpObject.responseText){
        document.getElementById("board").innerHTML = xmlHttpObject.responseText;
    }
}

function sendChatData(){
    var text = document.getElementById("text").value;
    xmlHttpObject = createXMLHttpRequest();
    xmlHttpObject.open("POST", "sendChatData.php", true);
    xmlHttpObject.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xmlHttpObject.send(
        "userId=" + encodeURIComponent(userId) +
        "&myId=" + encodeURIComponent(myId) +
        "&text=" + encodeURIComponent(text)
    );
    document.getElementById("text").value = ""; // フォームをクリアする
}

// 初回ロード時にチャットデータを取得
loadChatData();

// 3秒ごとにチャットの内容を取りに行く
setInterval(loadChatData, 2000);

// チャット履歴のリンクをクリックしたときに履歴を更新
document.querySelectorAll('.chat-container .chat').forEach(chat => {
    chat.addEventListener('click', function() {
        userId = this.querySelector('.chat-partner').innerText;
        document.getElementById('user_id').value = userId;
        loadChatData();
    });
});
</script>
</body>
</html>