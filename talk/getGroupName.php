<?php
require '../top/db-connect.php';

if (isset($_GET['group_id'])) {
    $group_id = $_GET['group_id'];

    try {
        $conn = new PDO($connect, USER, PASS);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        $sql = "SELECT group_name FROM group_chat WHERE id = :group_id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':group_id', $group_id, PDO::PARAM_STR);
        $stmt->execute();
        $group = $stmt->fetch(PDO::FETCH_ASSOC);
        $conn = null;

        if ($group) {
            echo htmlspecialchars($group['group_name'], ENT_QUOTES, 'UTF-8');
        } else {
            echo '不明なグループ';
        }
    } catch (PDOException $e) {
        echo "エラーが発生しました: " . $e->getMessage();
    }
} else {
    echo 'グループIDが指定されていません';
}
?>
