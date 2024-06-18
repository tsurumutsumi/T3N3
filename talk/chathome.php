<?=$msg?>
<head><meta charset="UTF-8"></head>

<!-- <form action="sendChatData.php" method="POST">
	<table summary="送信フォーム">
	<tr><th style="width:150px">名前(10文字以内)</th><td><input type="text" name="name" value="" style="width:100%" maxlength="10" required /></td></tr>
	<tr><th>文章(50文字以内)</th><td><input type="text" name="text" value="" style="width:100%" maxlength="50" required /></td></tr>
	</table>
	<p><input type="submit" value="送信" class="button" /></p>
</form> -->
<form onsubmit="sendChatData();return false">
<table summary="送信フォーム">
<tr><th style="width:150px">名前(10文字以内)</th><td><input type="text" name="name" value="" style="width:100%" maxlength="10" required /></td></tr>
<tr><th>文章(50文字以内)</th><td><input type="text" name="text" value="" style="width:100%" maxlength="50" required /></td></tr>
</table>
<p><input type="submit" value="送信" class="button" /></p>
</form>

<table summary="チャット">
<tr><th style="width:150px">名前</th><th style="width:180px">投稿日時</th><th>文章</th></tr>
<tbody id="board">

<!-- // URLパラメータからユーザーIDを取得 -->
<?if (isset($_GET['user_id'])) {
	$user_id = $_GET['user_id'];
	
} else {
    echo 'ユーザーIDが指定されていません';
    exit;
}?>

<?php foreach($_chat as $val){?>
<tr><td><?=htmlspecialchars($val["user_name"])?></td><td><?=substr($val["date"],5,14)?></td><td><?=htmlspecialchars($val["text"])?></td></tr>
<?php } ?>
</tbody>
</table>

<script type="text/javascript">

// 名前か文章にカーソルをフォーカス
if(document.getElementsByName("text")[0]) document.getElementsByName("text")[0].focus();
if(document.getElementsByName("name")[0]) document.getElementsByName("name")[0].focus();

// xmlHttpObjectの作成
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

// はじめだけのチャットの内容の取得
function chat(){
	xmlHttpObject = createXMLHttpRequest();
	xmlHttpObject.onreadystatechange = displayHtml;
	xmlHttpObject.open("GET","caht.php",true);
	xmlHttpObject.send(null);
}

// チャットの内容の取得
function loadChatData(){
	xmlHttpObject = createXMLHttpRequest();
	xmlHttpObject.onreadystatechange = displayHtml;
	xmlHttpObject.open("GET","loadChatData.php",true);
	xmlHttpObject.send(null);
}

// 新たな書き込みがあった場合に表示する
function displayHtml(){
	if((xmlHttpObject.readyState == 4) && (xmlHttpObject.status == 200) && xmlHttpObject.responseText){
		document.getElementById("board").innerHTML = xmlHttpObject.responseText + document.getElementById("board").innerHTML;
	}
}

// チャットに書き込みをする
function sendChatData(){
	xmlHttpObject = createXMLHttpRequest();
	xmlHttpObject.open("POST","sendChatData.php",true);
	xmlHttpObject.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	xmlHttpObject.send("name="+encodeURIComponent(document.getElementsByName("name")[0].value)+"&text="+encodeURIComponent(document.getElementsByName("text")[0].value));
	document.getElementsByName("text")[0].value = "";
	loadChatData();
}

// 3秒ごとにチャットの内容を取りに行く
setInterval('loadChatData()',3000);

</script>