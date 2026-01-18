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

    // 分页链接样式初始化
    this.paginationLinkInit();
    // 表格样式初始化
    this.tableInit();
  }

  /**
   * 分页链接样式初始化
   */
  paginationLinkInit() {
    if ($('.pagination li').length) {
      $('.pagination li').addClass('page-item');
      $('.pagination li a').addClass('page-link');
      $('.pagination .active a').attr('aria-current', 'page');
      if ($('.pagination .prev').length) {
        $('.pagination .prev a').attr({
          'aria-label': window.t.previousPage,
          'title': window.t.previousPage,
          'data-toggle': 'tooltip',
          'data-placement': 'top'
        });
      }
      if ($('.pagination .next').length) {
        $('.pagination .next a').attr({
          'aria-label': window.t.nextPage,
          'title': window.t.nextPage,
          'data-toggle': 'tooltip',
          'data-placement': 'top'
        });
      }
    }else {
      $('.page-nav').remove();
    }
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