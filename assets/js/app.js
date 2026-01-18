/*!
* HomePage: https://www.misterma.com
* GithubPage: https://github.com/changbin1997
* ProjectPage: https://github.com/changbin1997/Facile
* author: Changbin (changbin1997)
* Licensed under MIT
*/

import Lightbox from './modules/Lightbox.js';
import Emoji from './modules/Emoji.js';
import Directory from './modules/Directory.js';
import accessibilityInit from './modules/accessibilityInit.js';
import ThemeColor from './modules/ThemeColor.js';
import codeHighlightInit from './modules/codeHighlightInit.js';
import BootstrapStyle from './modules/BootstrapStyle.js';
import PJAX from './modules/PJAX.js';
import AvatarStyle from './modules/AvatarStyle.js';
import ArticleEngagement from './modules/ArticleEngagement.js';

$(function () {
  let inputFocus = false;  // 表单焦点状态

  // 图片灯箱初始化
  const lightbox = new Lightbox();
  lightbox.init();

  // Emoji初始化
  const emoji = new Emoji();
  emoji.init();

  // 目录初始化
  const directory = new Directory();
  directory.init();

  // 主题配色初始化
  const themeColor = new ThemeColor();
  themeColor.init();

  // 一些 bootstrap 的样式初始化
  const bootstrapStyle = new BootstrapStyle();
  bootstrapStyle.init();

  // 头像样式初始化
  const avatarStyle = new AvatarStyle();
  avatarStyle.init();

  // 给文章中的代码块添加拷贝按钮和拷贝事件
  codeHighlightInit();;

  // 点赞初始化
  ArticleEngagement.likeInit();

  // 一些可访问性相关的功能初始化
  accessibilityInit();

  // 生成文章的分享二维码
  ArticleEngagement.shareQrCode();

  // 图片懒加载
  lazyLoadImages();

  // 表单焦点事件初始化
  inputFocusInit();

  // pjax 初始化
  const pjax = new PJAX();
  pjax.init(() => {
    // PJAX 替换完成后
    // 代码高亮初始化
    codeHighlightInit();
    // 头像样式初始化
    avatarStyle.init();
    // 点赞初始化
    ArticleEngagement.likeInit();
    // Emoji 面板初始化
    emoji.init();
    // 一些可访问性相关的功能初始化
    accessibilityInit();
    // 生成文章的分享二维码
    ArticleEngagement.shareQrCode();
    // 图片懒加载
    lazyLoadImages();
    // 表单焦点初始化
    inputFocusInit();
    // 图片灯箱初始化
    lightbox.init();
    // 目录初始化
    directory.init();
    // 主题配色初始化
    themeColor.init();
    // 一些 bootstrap 样式初始化
    bootstrapStyle.init();

    // 侧边栏的语言更改
    $('.sidebar .change-language').on('change', changeLanguage);
  });

  // 导航栏的切换语言点击
  $('header .change-language').on('click', changeLanguage);

  // 侧边栏的语言更改
  $('.sidebar .change-language').on('change', changeLanguage);

  // 窗口尺寸改变事件
  window.addEventListener('resize', () => {
    // 调整侧边栏目录的高度
    directory.directorySize();
  });

  // 全局快捷键
  $(document).on('keyup', ev => {
    // 如果按下的是右方向键就跳转到下一页
    if ((ev.keyCode === 39 || ev.key === 'ArrowRight') && !inputFocus && !lightbox.isShow) {
      // 文章列表页面跳转
      if ($('.next .page-link').length) {
        location.href = $('.next .page-link').attr('href');
      }
      // 文章页内容翻页
      if ($('.post-pagination .next-page').length) {
        location.href = $('.post-pagination .next-page').attr('href');
      }
    }
    // 如果按下的是左方向键就跳转到上一页
    if ((ev.keyCode === 37 || ev.key === 'ArrowLeft') && !inputFocus && !lightbox.isShow) {
      // 文章列表页面跳转
      if ($('.prev .page-link').length) {
        location.href = $('.prev .page-link').attr('href');
      }
      // 文章页内容翻页
      if ($('.post-pagination .previous-page').length) {
        location.href = $('.post-pagination .previous-page').attr('href');
      }
    }
  });

  // 页面空白区域点击
  $('body').on('click', () => {
    // 如果表情面板处于开启状态就关闭表情面板
    if (emoji.isShow) $('#show-emoji-btn').click();
  });

  // 评论内容输入框点击
  $('#textarea').on('click', () => {
    return false;
  });

  // 监听滚动条
  $(document).on('scroll', () => {
    // 返回顶部的按钮是否存在
    if ($('#to-top-btn').length) {
      // 如果滚动条高度 > 屏幕高度
      if ($(document).scrollTop() > window.innerHeight) {
        // 显示返回顶部按钮
        $('#to-top-btn').removeClass('d-none');
      }else {
        // 隐藏返回顶部按钮
        $('#to-top-btn').addClass('d-none');
      }
    }

    // 检测文章图片位置
    $('.load-img').each(function() {
      // 如果文章内的 img 进入可视区就加载图片
      if (
        $(this).offset().top < $(document).scrollTop() + window.innerHeight &&
        $(this).offset().top + $(this).height() > $(document).scrollTop()
      ) {
        if ($(this).attr('src') === undefined) {
          $(this).attr('src', $(this).attr('data-src'));
        }
      }
    });

    // 固定或取消固定侧边栏目录
    directory.directoryPosition();
  });

  // 返回顶部按钮点击
  $('#to-top-btn').on('click', () => {
    // 返回顶部，让第一个链接获取焦点
    $('html').animate({
      scrollTop: 0
    }, 400);
    $('header .navbar-brand').get(0).focus();
    return false;
  });

  // 下面是一些用于样式和功能初始化的函数
  // 图片懒加载
  function lazyLoadImages() {
    // 如果页面加载完成时有图片在可视区就直接加载图片
    $('.load-img').each(function() {
      if ($(this).offset().top < window.innerHeight) {
        $(this).attr('src', $(this).attr('data-src'));
      }
    });

    // 文章图片加载完成后删除默认样式
    $('.load-img').on('load', function() {
      $(this).removeClass('load-img');
    });
  }

  // 表单焦点事件初始化
  function inputFocusInit() {
    // 输入框获取焦点
    $('input[type="search"], input[type="text"], input[type="email"], input[type="url"], textarea').on('focus', () => {
      inputFocus = true;
    });
    // 输入框失去焦点
    $('input[type="search"], input[type="text"], input[type="email"], input[type="url"], textarea').on('blur', () => {
      inputFocus = false;
    });
  }

  // 更改语言
  function changeLanguage(ev) {
    const language = $(ev.target).attr('data-language');
    // 获取当前的时间戳
    let time = Date.parse(new Date());
    // 在当前的时间戳上 + 180天
    time += 15552000000;
    time = new Date(time);
    // 写入 cookie
    document.cookie = `language=${language};path=/;expires=Tue,${time}`;
    location.reload();
  }
});