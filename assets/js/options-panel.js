window.onload = function () {
  var title = ['外观', '站点信息', '导航', '侧边栏', '文章相关', '评论', '友情链接', '开发者'];  // 组标题
  var ul = document.querySelectorAll('form ul');  // 列表
  var form = document.querySelector('.typecho-page-main form');
  var titleEl = [];

  // 生成分组标题
  title.forEach(function (val) {
    var h2El = document.createElement('h2');
    h2El.innerHTML = val;
    titleEl.push(h2El);
  });

  for (var i = 0;i < ul.length;i ++) {
    // 给分组加入屏幕阅读器专用的标签
    ul[i].setAttribute('aria-label', ul[i].children[0].children[0].innerHTML);
  }

  // 插入分组标题
  form.insertBefore(titleEl[0], ul[0]);  // 外观
  form.insertBefore(titleEl[1], ul[1]);  // 站点信息
  form.insertBefore(titleEl[2], ul[4]);  // 导航
  form.insertBefore(titleEl[3], ul[5]);  // 侧边栏
  form.insertBefore(titleEl[4], ul[7]);  // 文章相关
  form.insertBefore(titleEl[5], ul[13]);  // 评论
  form.insertBefore(titleEl[6], ul[17]);  // 友链
  form.insertBefore(titleEl[7], ul[20]);  // 开发者

  // 导出配置按钮点击
  document.querySelector('#export-btn').addEventListener('click', function() {
    var input = document.querySelectorAll('form input');  // 获取所有 input
    var textarea = document.querySelectorAll('form textarea');  // 获取所有 textarea
    var backup = [];  // 主题配置内容

    // 获取 input 的内容
    for (var i = 0;i < input.length;i ++) {
      // 导出 type 为 text 的 input
      if (input[i].getAttribute('type') === 'text') {
        backup.push({
          name: input[i].name,
          value: encodeURIComponent(input[i].value),
          type: input[i].getAttribute('type')
        });
      }

      // 导出 radio 的 input
      if (input[i].getAttribute('type') === 'radio') {
        backup.push({
          name: input[i].name,
          value: input[i].value,
          type: input[i].getAttribute('type'),
          checked: input[i].checked
        });
      }

      // 导出 checkbox 的 input
      if (input[i].getAttribute('type') === 'checkbox') {
        backup.push({
          name: input[i].name,
          value: input[i].value,
          type: input[i].getAttribute('type'),
          checked: input[i].checked
        });
      }
    }

    // 获取 textarea 的内容
    for (var i = 0;i < textarea.length;i ++) {
      backup.push({
        name: textarea[i].name,
        value: encodeURIComponent(textarea[i].value),
        type: textarea[i].tagName
      });
    }

    backup = JSON.stringify(backup);
    var blob = new Blob([backup]);
    document.querySelector('#download-file').href = URL.createObjectURL(blob);
    document.querySelector('#download-file').download = 'facile-config.json';
    document.querySelector('#download-file').click();
  });

  // 导入配置按钮点击
  document.querySelector('#import-btn').addEventListener('click', function() {
    document.querySelector('#file-select').click();
  });

  // 文件选择完成
  document.querySelector('#file-select').addEventListener('change', function() {
    if (this.value === '') {
      return false;
    }

    var reader = new FileReader();
    reader.readAsText(this.files[0]);

    reader.addEventListener('load', function(ev) {
      var config = JSON.parse(ev.target.result);
      var input = document.querySelectorAll('form input');  // 获取所有 input
      var textarea = document.querySelectorAll('form textarea');  // 获取所有 textarea

      config.forEach(function(val) {
        // 设置 input 的 value
        for (var i = 0;i < input.length;i ++) {
          // 设置 text input 的 value
          if (input[i].getAttribute('type') === 'text' && input[i].name === val.name) {
            input[i].value = decodeURIComponent(val.value);
          }

          // 设置 radio 的选中状态
          if (input[i].getAttribute('type') === 'radio') {
            if (input[i].name === val.name && input[i].value === val.value) {
              input[i].checked = val.checked;
            }
          }

          // 设置 checkbox 的选中状态
          if (input[i].getAttribute('type') === 'checkbox') {
            if (input[i].name === val.name && input[i].value === val.value) {
              input[i].checked = val.checked;
            }
          }
        }

        for (var i = 0;i < textarea.length;i ++) {
          if (textarea[i].name === val.name && textarea[i].tagName === val.type) {
            textarea[i].value = decodeURIComponent(val.value);
          }
        }
      });

      if (confirm('主题配置信息已成功导入，您确定要保存设置吗？')) {
        document.querySelector('.typecho-page-main form').submit();
      }
    });

    reader.addEventListener('error', function() {
      alert('读取文件时发生错误！');
    });
  });
};