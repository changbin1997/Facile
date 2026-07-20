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
      // 跟随系统
      // 检查浏览器是否支持跟随系统的配色模式
      if (this.isColorSchemeSupported()) {
        // 检测系统配色模式
        const darkColor = window.matchMedia('(prefers-color-scheme: dark)');
        if (darkColor.matches) {
          // 深色
          this.themeColor = 'dark';
        }else {
          // 浅色
          this.themeColor = 'light';
        }
      }else {
        // 如果浏览器不支持跟随系统配色就使用浅色模式
        this.themeColor = 'light';
        // 更改配色
        $('body').removeClass($('body').attr('data-color'));
        $('body').addClass('light-color');
        $('body').attr('data-color', 'light-color');
      }
    }

    // 根据当前使用的主题配色来设置侧边栏主题单选框的选中状态
    if ($('.change-color').length) {
      this.themeColor === 'dark' ? $('#dark-color').attr('checked', true) : $('#light-color').attr('checked', true);
    }

    // 根据当前使用的主题配色来设置代码高亮的配色
    this.codeHighlightColor();

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
      // 重新设置代码块配色
      this.codeHighlightColor();
      // 如果设置了导航栏图片 logo 就更改 logo 图片
      if ($('.logo-img').length) {
        const dataUrl = color === 'dark-color' ? 'data-dark-url' : 'data-light-url';
        $('.logo-img').attr('src', $('.logo-img').attr(dataUrl));
      }
    });

    // 评论回复对象名字鼠标移入和移出
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

    // 系统配色模式改变时调整配色模式的状态
    if (this.isColorSchemeSupported()) {
      const mediaQuery = window.matchMedia('(prefers-color-scheme: dark)');

      // 定义系统配色改变时的处理逻辑
      const handleSystemThemeChange = (e) => {
        // 更新当前的配色状态
        this.themeColor = e.matches ? 'dark' : 'light';
        const colorClass = this.themeColor + '-color';

        // 更改 body 配色类和自定义属性
        $('body').removeClass($('body').attr('data-color'));
        $('body').addClass(colorClass);
        $('body').attr('data-color', colorClass);

        // 重新设置配色单选框的选中状态 (使用 prop 确保 DOM 状态正确更新)
        $(`#${this.themeColor}-color`).prop('checked', true);

        // 重新调用代码高亮设置
        this.codeHighlightColor();
      };

      // 兼容性处理：现代浏览器使用 addEventListener，老版浏览器使用 addListener
      if (mediaQuery.addEventListener) {
        mediaQuery.addEventListener('change', handleSystemThemeChange);
      } else if (mediaQuery.addListener) {
        mediaQuery.addListener(handleSystemThemeChange);
      }
    }
  }

  /**
   * 检查浏览器是否支持跟随系统的配色模式
   * @returns {boolean}
   */
  isColorSchemeSupported() {
    try {
      // 检查 matchMedia 是否存在 (IE9 及其以上支持 matchMedia，但不支持 prefers-color-scheme)
      if (typeof window !== 'undefined' && window.matchMedia) {
        const mediaQuery = window.matchMedia('(prefers-color-scheme: dark)');
        // 检查返回的 media 字符串。
        // 如果浏览器不支持该查询，media 属性通常会返回 "not all" 或原始字符串但无效。
        // 在完全不支持的浏览器中，matches 也会一直是 false。
        return mediaQuery.media !== 'not all';
      }
      return false;
    }catch (error) {
      return false;
    }
  }

  /**
   * 根据主题配色模式设置代码高亮主题
   * @returns {boolean} 如果代码块配色不是跟随主题配色就直接返回
   */
  codeHighlightColor() {
    // 如果代码块主题不是跟随主题就直接返回
    if ($('.follow-theme-color').length < 1) return false;
    // 移除可能添加的代码配色，方便切换主题配色的时候能同时切换代码块配色
    $('body').removeClass('stackoverflow-light');
    $('body').removeClass('vs2015');
    if (this.themeColor === 'light') {
      $('body').addClass('stackoverflow-light');
    }else {
      $('body').addClass('vs2015');
    }
  }
}