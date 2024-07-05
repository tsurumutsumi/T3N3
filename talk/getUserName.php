<?php
require '../top/db-connect.php';

if (isset($_GET['user_id'])) {
    $user_id = $_GET['user_id'];

    try {
        $conn = new PDO($connect, USER, PASS);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        $sql = "SELECT * FROM user_management WHERE user_id = :user_id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_STR);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        $conn = null;

        if ($user) {
            echo htmlspecialchars($user['user_id'], ENT_QUOTES, 'UTF-8');
        } else {
            echo '不明なユーザー';
        }
    } catch (PDOException $e) {
        echo "エラーが発生しました: " . $e->getMessage();
    }
} else {
    echo 'ユーザーIDが指定されていません';
}
?>
