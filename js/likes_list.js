function changeText(button, isHovering) {
    if (isHovering) {
      // カーソルがボタンに乗った時
      if (button.classList.contains('return_button')) {
        button.textContent = "▶戻る";
      } 
    } else {
      // カーソルがボタンから離れた時
      if (button.classList.contains('return_button')) {
        button.textContent = "戻る";
      } 
    }
}