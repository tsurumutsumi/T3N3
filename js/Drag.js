// HTML コードの確認用
const updateHTMLCode = () => {
  const container = document.getElementById('my-container');
  document.getElementById('html-code').innerText = html_beautify(
    container.innerHTML, {
      indent_size: 2,
      end_with_newline: true,
      preserve_newlines: false,
      max_preserve_newlines: 0,
      wrap_line_length: 0,
      wrap_attributes_indent_size: 0,
      unformatted: ['b', 'em']
    }
  );
}

// ファイル選択ボタン, ドラッグ＆ドロップ領域のクリックでファイル選択ダイアログ
document.querySelectorAll('#file-select-button, #drag-and-drop-area').forEach((ele) => {
    ele.addEventListener('click', () => {
      document.getElementById('file-select-input').click();
    });
  });
  
  // ファイル選択後
  document.getElementById('file-select-input').addEventListener('change', (event) => {
    const files = event.target.files;
    if (files.length > 0) {
      previewAndInsert(files);
    }
    event.target.files = null;
    event.target.value = null;
  });
  
  const dragAndDropArea = document.getElementById('drag-and-drop-area');
  
  // ドラッグ中
  dragAndDropArea.addEventListener('dragover', (event) => {
    dragAndDropArea.classList.add('active');
    event.preventDefault();
    event.dataTransfer.dropEffect = 'copy';
  });
  
  // マウスがドラッグ＆ドロップ領域外に出たとき
  dragAndDropArea.addEventListener('dragleave', (event) => {
    dragAndDropArea.classList.remove('active');
  });
  
  // ドロップ時
  dragAndDropArea.addEventListener('drop', (event) => {
    event.preventDefault();
    dragAndDropArea.classList.remove('active');
    const files = event.dataTransfer.files;
    if (files.length === 0) {
      return;
    }
  
    // 画像ファイルのみ OK
    if (!files[0].type.match(/image\/*/)) {
      return;
    }
  
    previewAndInsert(files);
  });
  
  // 画像プレビューと input 追加
  const previewAndInsert = (files) => {
    const file = files[0];
  
    const wrapper = document.createElement('div');
  
    const input = document.createElement('input');
    input.type = 'file';
    input.classList.add('hidden');
    // https://qiita.com/jkr_2255/items/1c30f7afefe6959506d2
    if (files.length > 1 && typeof DataTransfer !== 'undefined') {
      const dt = new DataTransfer();
      dt.items.add(files[0]);
      input.files = dt.files;
    } else {
      input.files = files;
    }
    wrapper.appendChild(input);
  
    const img = document.createElement('img');
    const reader = new FileReader();
    reader.onload = (event) => {
      img.src = event.target.result;
      updateHTMLCode();
    }
    reader.readAsDataURL(file);
    wrapper.appendChild(img);
  
    document.getElementById('previews').appendChild(wrapper);
  }
  
  document.body.onload = () => {
    updateHTMLCode();
  };