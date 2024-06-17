document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.follow_button').forEach(button => {
        button.addEventListener('click', function() {
            var userId = this.getAttribute('data-user-id');
            var action = this.src.includes('hito_gray.png') ? 'follow' : 'unfollow'; // 画像の状態でアクションを決定

            console.log('Button clicked');  // デバッグ用
            console.log('User ID:', userId);  // デバッグ用
            console.log('Action:', action);  // デバッグ用

            var xhr = new XMLHttpRequest();
            xhr.open("POST", "follow/follow.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    console.log('Response received:', xhr.responseText);  // デバッグ用
                    var response = JSON.parse(xhr.responseText);
                    if (response.success) {
                        if (action === 'follow') {
                            button.src = 'img/hito_blue.png'; // 画像を変更
                        } else {
                            button.src = 'img/hito_gray.png'; // 画像を変更
                        }
                    } else {
                        alert(response.message);
                    }
                }
            };

            xhr.send("action=" + action + "&followed_id=" + userId);
        });
    });
});
