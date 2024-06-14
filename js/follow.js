document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.follow_button').forEach(button => {
        button.addEventListener('click', function() {
            var userId = this.getAttribute('data-user-id');
            var action = this.src.includes('hito_gray.png') ? 'follow' : 'unfollow'; // 画像の状態でアクションを決定

            var xhr = new XMLHttpRequest();
            xhr.open("POST", "follow/follow.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    var response = JSON.parse(xhr.responseText);
                    if (response.success) {
                        if (action === 'follow') {
                            button.src = 'img/hito_blue.png'; // フォローした後の画像
                        } else {
                            button.src = 'img/hito_gray.png'; // アンフォローした後の画像
                        }
                    } else {
                        alert(response.message);
                    }
                }
            };

            xhr.send("user_id=" + userId + "&action=" + action);
        });
    });
});
