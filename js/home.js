function changeText(button, isHovering) {
    if (isHovering) {
      // カーソルがボタンに乗った時
      if (button.classList.contains('login_button')) {
        button.textContent = "▶ログイン";
      } 
    } else {
      // カーソルがボタンから離れた時
      if (button.classList.contains('login_button')) {
        button.textContent = "ログイン";
      } 
    }
}