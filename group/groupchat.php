<?php
session_start();
require '../top/db-connect.php';

if (!isset($_GET['group_id'])) {
    echo 'グループIDが指定されていません';
    exit;
}

$group_id = $_GET['group_id'];

// グループ名を取得
$dbh = new PDO($connect, USER, PASS);
$stmt = $dbh->prepare("SELECT group_name FROM group_chat WHERE id = ?");
$stmt->execute([$group_id]);
$group = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$group) {
    echo 'グループが見つかりません';
    exit;
}
?>
<h2><?php echo htmlspecialchars($group['group_name'], ENT_QUOTES, 'UTF-8'); ?></h2>
<form onsubmit="sendChatData(); return false;">
    <table summary="送信フォーム">
        <tr>
            <th>文章(50文字以内)</th>
            <td><input type="text" id="text" style="width:100%" maxlength="50" required /></td>
        </tr>
    </table>
    <p><input type="submit" value="送信" class="button" /></p>
</form>
<!-- 自分の名前 -->
<input type="hidden" id="my_id" value="<?php echo htmlspecialchars($_SESSION['user']['id'], ENT_QUOTES, 'UTF-8'); ?>">

<table summary="チャット">
    <tr>
        <th style="width:150px">名前</th><th style="width:180px">投稿日時</th><th>文章</th>
    </tr>
    <tbody id="board"></tbody>
</table>

<script type="text/javascript">
var groupId = <?php echo json_encode($group_id); ?>;
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
    xmlHttpObject.open("POST", 'g_loadChatData.php', true);
    xmlHttpObject.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xmlHttpObject.send("groupId=" + encodeURIComponent(groupId));
}

function displayHtml(){
    if((xmlHttpObject.readyState == 4) && (xmlHttpObject.status == 200) && xmlHttpObject.responseText){
        document.getElementById("board").innerHTML = xmlHttpObject.responseText;
    }
}

function sendChatData(){
    var text = document.getElementById("text").value;
    xmlHttpObject = createXMLHttpRequest();
    xmlHttpObject.open("POST", "g_sendChatData.php", true);
    xmlHttpObject.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xmlHttpObject.send(
        "groupId=" + encodeURIComponent(groupId) +
        "&message=" + encodeURIComponent(text)
    );
    document.getElementById("text").value = ""; // フォームをクリアする
}

// 初回ロード時にチャットデータ
loadChatData();

// 3秒ごとにチャットの内容を取りに行く
setInterval(loadChatData, 2000);
</script>
