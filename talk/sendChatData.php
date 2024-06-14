<?php
$name = isset($_POST["name"]) ? $_POST["name"] : "";
$text = isset($_POST["text"]) ? $_POST["text"] : "";

$err = array();
if (!$name) $err[] = "名前を入力してください";
if (mb_strlen($name) > 10) $err[] = "名前は10文字以内で入力してください";
if (!$text) $err[] = "文章を入力してください";
if (mb_strlen($text) > 50) $err[] = "文章は50文字以内で入力してください";

if (!count($err)) {
    try {
        $dbh = new PDO('mysql:host=mysql303.phy.lolipop.lan;dbname=LAA1517798-stp', 'LAA1517798', '0000');
        $stmt = $dbh->prepare("INSERT INTO chat (date, user_name, text) VALUES (NOW(), :name, :text)");
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':text', $text);
        $stmt->execute();
    } catch (PDOException $e) {
        die('接続エラー： ' . $e->getMessage());
    }
} else {
    $msg = showerr($err);
}

function showerr($err)
{
    $msg = "<ul>";
    foreach ($err as $e) {
        $msg .= "<li>" . htmlspecialchars($e) . "</li>";
    }
    $msg .= "</ul>";
    return $msg;
}
?>
