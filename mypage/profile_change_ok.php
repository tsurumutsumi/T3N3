<?php
session_start();
require '../top/db-connect.php';
require '../top/header.php';
echo '<link rel="stylesheet" href="../css/mypage_change.css">';

if(isset($_SESSION['user'])) {
    $pdo = new PDO($connect, USER, PASS);

    // フォームから送信されたデータを受け取る
    $icon = $_POST['icon'];
    $id = $_POST['id'];
    $name = $_POST['name'];
    $mail = $_POST['mail'];
    $bio = $_POST['bio'];
    $new_pass = $_POST['pass'];

    // パスワードが入力されている場合はハッシュ化
    if (!empty($new_pass)) {
        $hashed_pass = password_hash($new_pass, PASSWORD_DEFAULT);
    } else {
        // 入力されていない場合は既存のパスワードを使用
        $hashed_pass = $_SESSION['user']['pass'];
    }

    // プリペアドステートメントを使用してデータベースを更新
    $sql = $pdo->prepare('UPDATE user_management SET icon=?, user_name=?, mail=?, self_introduction=?, password=? WHERE user_id=?');
    $success = $sql->execute([$icon, $name, $mail, $bio, $hashed_pass, $id]);

    if ($success) {
        // セッション情報を更新
        $_SESSION['user']['icon'] = $icon;
        $_SESSION['user']['name'] = $name;
        $_SESSION['user']['mail'] = $mail;
        $_SESSION['user']['bio'] = $bio;
        $_SESSION['user']['pass'] = $hashed_pass;

        echo '<script>
            alert("プロフィールが更新されました。");
            window.location.href = "../mypage/mypage.php";
        </script>';
    } else {
        echo '<p>プロフィールの更新に失敗しました。</p>';
    }
} else {
    echo '<p>セッションが見つかりません。ログインしてください。</p>';
}

echo '<a href="../home.php">ホームへ</a>';
require '../top/footer.php';
?>
