function changeText(button, isHovering) {
  if (isHovering) {
    // カーソルがボタンに乗った時
    if (button.classList.contains('return_button')) {
      button.textContent = "▶戻る";
    } else if(button.classList.contains('update_button')){
      button.textContent = "▶更新";
    }
  } else {
    // カーソルがボタンから離れた時
    if (button.classList.contains('return_button')) {
      button.textContent = "戻る";
    } else if(button.classList.contains('update_button')){
      button.textContent = "更新";
    }
  }
}