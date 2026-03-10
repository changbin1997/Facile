/*!
* HomePage: https://www.misterma.com
* GithubPage: https://github.com/changbin1997
* ProjectPage: https://github.com/changbin1997/Facile
* author: Changbin (changbin1997)
* Licensed under MIT
*/

export default class BootstrapStyle {
  /**
   * 样式初始化
   */
  init() {
    // 初始化气球提示
    $('[data-toggle="tooltip"]').tooltip();

    // 给文章中的标签添加Bootstrap的样式
    if ($('.post-tag a').length) {
      $('.post-tag a').addClass('badge badge-dark');
    }

    // 表格样式初始化
    this.tableInit();
  }

  /**
   * 表格样式初始化
   */
  tableInit() {
    if ($('.post-content table').length) {
      for (var i = 0; i < $('.post-content table').length; i++) {
        //  生成 Bootstrap 的响应式表格
        const table = '<div class="table-responsive"><table class="table table-striped table-bordered table-hover">' + $('.post-content table').eq(i).html() + '</table></div>';
        $('.post-content table').eq(i).replaceWith(table);  //  替换文章中的表格
      }
    }
  }
}