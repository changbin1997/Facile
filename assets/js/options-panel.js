window.onload = function () {
  var title = ['站点信息', '侧边栏', '文章相关', '评论', '友情链接', '开发者'];  // 组标题
  var ul = document.querySelectorAll('form ul');  // 列表
  var form = document.querySelector('.typecho-page-main form');
  var titleEl = [];

  title.forEach(function (val) {
    var h2El = document.createElement('h2');
    h2El.innerHTML = val;
    titleEl.push(h2El);
  });

  for (var i = 0;i < ul.length;i ++) {
    ul[i].setAttribute('aria-label', ul[i].children[0].children[0].innerHTML);
  }

  // 插入分组标题
  form.insertBefore(titleEl[0], ul[0]);  // 站点信息
  form.insertBefore(titleEl[1], ul[3]);  // 侧边栏
  form.insertBefore(titleEl[2], ul[5]);  // 文章相关
  form.insertBefore(titleEl[3], ul[7]);  // 评论
  form.insertBefore(titleEl[4], ul[10]);  // 友链
  form.insertBefore(titleEl[5], ul[13]);  // 开发者
};