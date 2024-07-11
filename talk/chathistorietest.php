<script type="text/javascript">
var xmlHttpObject;
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
 


// groupIdがnull、空文字、またはundefinedでないかをチェック
if (groupId && groupId.trim() !== "" && groupId !== "null" && groupId !== "undefined") {
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
    } else if (userId && userId.trim() !== "" && userId !== "null" && userId !== "undefined") {
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
    } else {
        alert("送信先が指定されていません。");
    }