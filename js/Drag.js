document.addEventListener("DOMContentLoaded", function() {
  // ドラッグ＆ドロップ領域外のクリックイベントを無効化
  const uploadWrapper = document.querySelector('.upload-wrapper');
  uploadWrapper.addEventListener('click', function(event) {
      event.stopPropagation();
  });

  // ファイル選択時の処理と隠れたファイル入力への設定
  const fileInput = document.querySelector('#torokupic');
  const hiddenFileInput = document.createElement('input');
  hiddenFileInput.type = 'file';
  hiddenFileInput.classList.add('hidden');
  hiddenFileInput.addEventListener('change', function(event) {
      const files = event.target.files;
      if (files.length > 0) {
          previewAndInsert(files);
      }
  });
  fileInput.addEventListener('change', function(event) {
      hiddenFileInput.files = event.target.files;
  });

  // フォーム送信時の処理
  const uploadForm = document.getElementById('uploadForm');
  uploadForm.addEventListener('submit', function(event) {
      event.preventDefault();
      const formData = new FormData(this);
      formData.delete('pic'); // フォームデータから pic フィールドを削除
      const file = hiddenFileInput.files[0];
      if (file) {
          formData.append('pic', file); // 隠れたファイル入力からファイルを追加
      }
      fetch(this.action, {
          method: 'POST',
          body: formData
      }).then(response => {
          if (!response.ok) {
              throw new Error('Network response was not ok');
          }
          return response.text();
      }).then(data => {
          console.log(data);
          // フォーム送信後の処理を追加
      }).catch(error => {
          console.error('There was an error!', error);
      });
  });
});

// preview.js
const fileInput = document.querySelector('#torokupic');
fileInput.addEventListener('change', (event) => {
    const [file] = event.target.files;
    const figureImage = document.querySelector('#figureImage');
    const figure = document.querySelector('#figure');

    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            figureImage.setAttribute('src', e.target.result);
            figure.style.display = 'block';
        }
        reader.readAsDataURL(file);
    } else {
        figure.style.display = 'none';
    }
});
