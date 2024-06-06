function changeText(button, isHovering) {
  if (isHovering) {
    // カーソルがボタンに乗った時
    if (button.classList.contains('return_button')) {
      button.textContent = "▶戻る";
    }else if(button.classList.contains('post_button')){
      button.textContent = "▶投稿";
    }
  } else {
    // カーソルがボタンから離れた時
    if (button.classList.contains('return_button')) {
      button.textContent = "戻る";
    }else if(button.classList.contains('post_button')){
      button.textContent = "投稿";
    }
  }
}