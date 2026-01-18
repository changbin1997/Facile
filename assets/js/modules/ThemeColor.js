/*!
* HomePage: https://www.misterma.com
* GithubPage: https://github.com/changbin1997
* ProjectPage: https://github.com/changbin1997/Facile
* author: Changbin (changbin1997)
* Licensed under MIT
*/

export default class ThemeColor {
  themeColor = 'light';  // 主题配色方案

  /**
   * 主题配色初始化
   */
  init() {
    // 获取主题当前的配色模式
    if ($('.light-color').length) {
      this.themeColor = 'light';
    }else if ($('.dark-color').length) {
      this.themeColor = 'dark';
    }else {
      // 检测系统配色模式
      const darkColor = window.matchMedia('(prefers-color-scheme: dark)');
      if (darkColor.matches) {
        // 深色
        this.themeColor = 'dark';
      }else {
        // 浅色
        this.themeColor = 'light';
      }
    }

    // 根据当前使用的主题配色来设置侧边栏主题单选框的选中状态
    if ($('.change-color').length) {
      this.themeColor === 'dark' ? $('#dark-color').attr('checked', true) : $('#light-color').attr('checked', true);
    }

    // 切换主题的单选框改变
    $('.change-theme-color').on('click', ev => {
      // 获取选中的颜色
      const color = $(ev.target).attr('id');
      this.themeColor = color === 'dark-color' ? 'dark' : 'light';
      // 获取当前的时间戳
      let time = Date.parse(new Date());
      // 在当前的时间戳上 + 180天
      time += 15552000000;
      time = new Date(time);
      // 写入 cookie
      document.cookie = 'themeColor=' + color + ';path=/;expires=Tue,' + time;
      // 更改配色
      $('body').removeClass($('body').attr('data-color'));
      $('body').addClass(color);
      $('body').attr('data-color', color);
    });

    // 回复对象名字鼠标移入和移出
    $('#comments .parent').hover(ev => {
      // 根据主题配色模式设置高亮的颜色
      const commentItemBgColor = this.themeColor === 'dark' ? '#16161A' : '#F7E6D2';
      $($(ev.target).attr('href')).css('background', commentItemBgColor);
    }, ev => {
      $($(ev.target).attr('href')).css('background', 'none');
    });

    // 回复链接鼠标移入就高亮回复评论
    $('#comments .comment-reply a').hover(ev => {
      // 根据主题配色模式设置高亮的颜色
      const commentItemBgColor = this.themeColor === 'dark' ? '#16161A' : '#F7E6D2';
      $(ev.target).closest('.comment-box').css('background', commentItemBgColor);
    }, ev => {
      $(ev.target).closest('.comment-box').css('background', 'none');
    });
  }
}