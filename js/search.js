function changeText(button, isHovering) {
    if (isHovering) {
      // カーソルがボタンに乗った時
      if (button.classList.contains('home_button')) {
        button.textContent = "▶HOME";
      } 
    } else {
      // カーソルがボタンから離れた時
      if (button.classList.contains('home_button')) {
        button.textContent = "HOME";
      } 
    }
}