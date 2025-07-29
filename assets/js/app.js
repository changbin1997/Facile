/*!
* HomePage: https://www.misterma.com
* GithubPage: https://github.com/changbin1997
* ProjectPage: https://github.com/changbin1997/Facile
* author: Changbin (changbin1997)
* Licensed under MIT
*/

$(function () {
  let maxImg = false;  // 是否开启图片灯箱
  let directory = false;  // 是否打开移动设备的目录
  let emojiList = null;  // Emoji 列表
  let showEmoji = false;  // Emoji 面板状态
  const avatarColor = [];  // 存储文字头像颜色
  const avatarName = [];  // 存储文字头像名称
  let directoryTop = 0;  // 侧边栏章节目录的高度
  let commentParentId = null;  // 存储父评论的id，用于PJAX评论提交后跳转
  let themeColor = 'light';  // 存储主题配色
  let inputFocus = false;  // 表单焦点状态

  // 主题配色初始化
  themeColorInit();

  // 给分页链接添加class和aria属性
  paginationLinkInit();

  // 头像样式初始化
  avatarStyleInit();

  // 给文章内的表格添加 bootstrap 的样式
  tableInit();

  // 给文章中的代码块添加拷贝按钮和拷贝事件
  codeHighlightInit();

  // 图片灯箱初始化
  imageLightboxInit();

  // 点赞初始化
  likeInit();

  // Emoji 面板初始化
  emojiInit();

  // 一些可访问性相关的功能初始化
  accessibilityInit();

  // bootstrap 的一些样式初始化
  bootstrapStyleInit();

  // 文章内的章节目录跳转样式
  directoryStyleInit();

  // 生成文章的分享二维码
  shareQrCode();

  // 图片懒加载
  lazyLoadImages();

  // 表单焦点事件初始化
  inputFocusInit();

  // 导航栏的切换语言点击
  $('header .change-language').on('click', changeLanguage);

  // 侧边栏的语言更改
  $('.sidebar .change-language').on('change', changeLanguage);

  // 全局快捷键
  $(document).on('keyup', ev => {
    // 如果是 ESC 就关闭大图
    if (ev.keyCode === 27 && $('#max-img-box').length) {
      $('.max-img-features-btn .hide-img').click();
    }
    // 如果按下的是 + 就放大图片
    if (ev.keyCode === 107 && $('#max-img-box').length) {
      $('.max-img-features-btn .big').click();
    }
    // 如果按下的是 - 就缩小图片
    if (ev.keyCode === 109 && $('#max-img-box').length) {
      $('.max-img-features-btn .small').click();
    }
    // 如果按下的是右方向键就跳转到下一页
    if (ev.keyCode === 39 && !inputFocus) {
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
    if (ev.keyCode === 37 && !inputFocus) {
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
    if (showEmoji) $('#show-emoji-btn').click();
  });

  // 评论内容输入框点击
  $('#textarea').on('click', () => {
    return false;
  });

  // 页面加载完成后调整侧边栏目录的高度
  directorySize()

  // 窗口尺寸改变
  window.addEventListener('resize', () => {
    // 调整侧边栏目录的高度
    directorySize();
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

    // 固定侧边栏章节目录位置
    if ($('.sidebar .directory').length && window.innerWidth >= 992) {
      if ($(document).scrollTop() >= directoryTop) {
        $('.sidebar .directory').css({
          position: 'fixed',
          top: 80
        });
      }else {
        $('.sidebar .directory').css('position', 'static');
      }
    }
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

  // PJAX 链接初始化
  if ($('body').attr('data-pjax') === 'on') {
    pjaxLinkInit();
  }

  // 初始化 PJAX
  if ($('body').attr('data-pjax') === 'on') {
    $(document).pjax('.pjax-link', '#main', {
      fragment: '#main',
      timeout: 20000
    });
  }

  // PJAX 搜索表单提交
  if ($('body').attr('data-pjax') === 'on') {
    $(document).on('submit', 'form[role="search"]', ev => {
      $.pjax.submit(ev, '#main', {
        fragment: '#main',
        replace: false,
        timeout: 20000
      });
    });
  }

  // PJAX 评论表单提交
  if ($('body').attr('data-pjax') === 'on') {
    $(document).on('submit', '#comment-form', ev => {
      // 如果是回复评论就存储父评论的id
      if ($('#comment-parent').length && $('#comment-parent').val() !== '') {
        commentParentId = $('#comment-parent').val();
      }

      $.pjax.submit(ev, '#main', {
        fragment: '#main',
        replace: false,
        push: false,
        timeout: 20000
      });
    });
  }

  // PJAX 即将开始请求
  if ($('body').attr('data-pjax') === 'on') {
    $(document).on('pjax:start', () => {
      // 如果开启了移动设备的导航菜单就关闭菜单
      if ($('.navbar-toggler').attr('aria-expanded') === 'true') {
        $('.navbar-toggler').click();
      }
      // 移除工具提示
      $('[data-toggle="tooltip"]').tooltip('dispose');
      // 显示进度条
      if ($('#progress-bar').length) {
        $('#progress-bar').show();
      }
    });
  }

  // PJAX 开始请求
  if ($('body').attr('data-pjax') === 'on') {
    $(document).on('pjax:send', () => {
      if ($('#progress-bar').length) {
        // 更改进度条
        $('#progress-bar #progress').animate({
          width: '30%'
        }, 100);
        $('#progress-bar #progress').attr('aria-valuenow', '30');
      }
    });
  }

  // PJAX 请求完成
  if ($('body').attr('data-pjax') === 'on') {
    $(document).on('pjax:complete', () => {
      if ($('#progress-bar').length) {
        // 更改进度条
        $('#progress-bar #progress').animate({
          width: '80%'
        }, 200);
        $('#progress-bar #progress').attr('aria-valuenow', '80');
      }
    });
  }

  // PJAX 替换完成
  if ($('body').attr('data-pjax') === 'on') {
    $(document).on('pjax:end', (ev) => {
      // 隐藏进度条
      if ($('#progress-bar').length) {
        $('#progress-bar #progress').animate({
          width: '100%'
        }, 100, () => {
          $('#progress-bar').hide();
          $('#progress-bar #progress').css('width', '0');
          $('#progress-bar #progress').attr('aria-valuenow', '0');
        });
        $('#progress-bar #progress').attr('aria-valuenow', '100');
      }

      // 清除导航栏链接的选中状态
      $('.navbar-nav .nav-item').removeClass('active');
      $('.navbar-nav .nav-item a').removeAttr('aria-current');
      // 重新设置导航栏链接的选中状态
      for (let i = 0;i < $('.navbar-nav .nav-item a').length;i ++) {
        if ($('.navbar-nav .nav-item a').eq(i).attr('href') === ev.currentTarget.URL) {
          $('.navbar-nav .nav-item').eq(i).addClass('active');
          $('.navbar-nav .nav-item a').eq(i).attr('aria-current', 'page');
          break;
        }
      }

      // 如果是评论提交就滚动到评论区
      if (ev.relatedTarget.id === 'comment-form') {
        if (commentParentId !== null && $(`#comment-${commentParentId}`).length) {
          // 如果是回复评论就滚动到父评论的区域
          $('html, body').animate({
            scrollTop: $(`#comment-${commentParentId}`).offset().top
          }, 250);
        }else {
          // 如果是评论提交就滚动到评论区
          $('html, body').animate({
            scrollTop: $('#comments').offset().top
          }, 250);
        }
        commentParentId = null;
      }

      // 表格初始化
      tableInit();
      // 代码高亮初始化
      codeHighlightInit();
      // 图片灯箱初始化
      imageLightboxInit();
      // 分页链接初始化
      paginationLinkInit();
      // 调整章节目录的尺寸
      directorySize();
      // 文章章节目录跳转样式
      directoryStyleInit();
      // 头像样式初始化
      avatarStyleInit();
      // 点赞初始化
      likeInit();
      // Emoji 面板初始化
      emojiInit();
      // 一些可访问性相关的功能初始化
      accessibilityInit();
      // 初始化 bootstrap 的一些样式
      bootstrapStyleInit();
      // 主题配色初始化
      themeColorInit();
      // 给 PJAX 链接添加 class
      pjaxLinkInit();
      // 生成文章的分享二维码
      shareQrCode();
      // 图片懒加载
      lazyLoadImages();
      // 表单焦点初始化
      inputFocusInit();

      // 侧边栏的语言更改
      $('.sidebar .change-language').on('change', changeLanguage);
    });
  }

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

  // 给 PJAX 链接添加 class
  function pjaxLinkInit() {
    const currentDomain = window.location.hostname;

    $('a').each((index, element) => {
      const href = $(element).attr('href');
      const target = $(element).attr('target');

      // 检查链接是否包含当前域名，且不含有 target="_blank"
      if (href && href.includes(currentDomain) && !target) {
        $(element).addClass('pjax-link');
      }
    });
  }

  // 生成分享二维码
  function shareQrCode() {
    if ($('#qr') !== undefined) {
      const qr = new QRious({
        element: $('#qr').get(0),
        value: location.href,
        size: 150
      });
    }
  }

  // 一些可访问性相关的功能初始化
  function accessibilityInit() {
    // 文章是否加密
    if ($('.post-content .protected').length) {
      $('input[name="protectPassword"]').attr('placeholder', window.t.enterYourPassword);
      $('input[name="protectPassword"]').focus();
      $('.protected .submit').val(window.t.submit);
      $('.protected .word').html(window.t.enterThePasswordToViewIt);
    }

    // 给文章内的链接添加 target 属性
    if ($('.post-page .post-content a').length) {
      $('.post-content a').attr('target', '_blank');
    }

    // 给评论区的评论者链接添加 target 属性
    if ($('.comment-info .author a').length) {
      $('.comment-info .author a').attr('target', '_blank');
    }

    // 评论列表的回复链接点击
    $('.comment-reply').on('click', () => {
      if ($('.comment-list .comment-input').length && $('#cancel-comment-reply-link').length) {
        $('#cancel-comment-reply-link').addClass('btn btn-outline-primary ml-2');
        $('#cancel-comment-reply-link').attr('role', 'button');
        $('#cancel-comment-reply-link').html(window.t.cancelReply);
      }
    });

    // 取消回复链接按下 tab
    $('#cancel-comment-reply-link').on('keydown', ev => {
      if (ev.keyCode === 9) {
        // 让评论内容输入框获取焦点
        ev.preventDefault();
        $('#textarea').focus();
      }
    });

    // 给评论列表添加描述
    if ($('.comments-lists > ol').length) {
      $('.comments-lists > ol').attr('aria-label', '评论区');
    }

    // 回复对象名字鼠标移入和移出
    $('#comments .parent').hover(ev => {
      // 根据主题配色模式设置高亮的颜色
      const commentItemBgColor = themeColor === 'dark' ? '#16161A' : '#F7E6D2';
      $($(ev.target).attr('href')).css('background', commentItemBgColor);
    }, ev => {
      $($(ev.target).attr('href')).css('background', 'none');
    });

    // 回复链接鼠标移入就高亮回复评论
    $('#comments .comment-reply a').hover(ev => {
      // 根据主题配色模式设置高亮的颜色
      const commentItemBgColor = themeColor === 'dark' ? '#16161A' : '#F7E6D2';
      $(ev.target).closest('.comment-box').css('background', commentItemBgColor);
    }, ev => {
      $(ev.target).closest('.comment-box').css('background', 'none');
    });

    // 给回复链接添加 title 描述
    $('.comment-reply').each(function() {
      const authorName = $(this).closest('.comment-box').find('.author a').text() ||
          $(this).closest('.comment-box').find('.author').text();
      $(this).find('a').attr({
        title: `${window.t.replyTo} ${authorName}`,
        'data-toggle': 'tooltip',
        'data-placement': 'top'
      });
    });

    // 给上一篇文章和下一篇文章的链接添加文字描述
    $('.previous a').attr('aria-describedby', 'previous-post-text');
    $('.next a').attr('aria-describedby', 'next-post-text');

    // 给评论区的作者评论链接添加作者 title
    $('.author-tag').each(function() {
      $(this).closest('.comment-info').find('.author a').attr('title', '作者');
    });
  }

  // 主题配色初始化
  function themeColorInit() {
    // 获取主题当前的配色模式
    if ($('.light-color').length) {
      themeColor = 'light';
    }else if ($('.dark-color').length) {
      themeColor = 'dark';
    }else {
      // 检测系统配色模式
      const darkColor = window.matchMedia('(prefers-color-scheme: dark)');
      if (darkColor.matches) {
        // 深色
        themeColor = 'dark';
      }else {
        // 浅色
        themeColor = 'light';
      }
    }

    // 根据当前使用的主题配色来设置侧边栏主题单选框的选中状态
    if ($('.change-color').length) {
      themeColor === 'dark' ? $('#dark-color').attr('checked', true) : $('#light-color').attr('checked', true);
    }

    // 切换主题的单选框改变
    $('.change-theme-color').on('click', ev => {
      // 获取选中的颜色
      const color = $(ev.target).attr('id');
      themeColor = color === 'dark-color' ? 'dark' : 'light';
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
  }

  // 一些 bootstrap 的样式初始化
  function bootstrapStyleInit() {
    // 初始化气球提示
    $('[data-toggle="tooltip"]').tooltip();

    // 给文章中的标签添加Bootstrap的样式
    if ($('.post-tag a').length) {
      $('.post-tag a').addClass('badge badge-dark');
    }
  }

  // Emoji 面板初始化
  function emojiInit() {
    if ($('#emoji-panel').length) {
      // 如果开启了 Emoji 面板就加载 Emoji
      $.ajax({
        type: 'post',
        url: $('#show-emoji-btn').attr('data-url'),
        data: 'emoji=emoji',
        timeout: 10000,
        global: false,
        success: data => {
          data = JSON.parse(data);
          // 检查是否加载正确
          if (data.smileys === undefined) {
            $('#emoji-panel').append('<div>未知错误！</div>');
            return false;
          }
          emojiList = data;
          // 调用面目表情按钮事件
          $('#emoji-classification button').eq(0).click();
        },
        error: (xhr, err, abnormal) => {
          $('#emoji-panel').append('<div>服务器请求出错！错误代码' + err + '</div>');
        }
      });

      // Emoji 开关点击
      $('#show-emoji-btn').on('click', ev => {
        // 设置 Emoji 面板的显示和隐藏
        $('#emoji-panel').slideToggle(250);
        // 设置 Emoji 的显示和隐藏状态
        showEmoji = !showEmoji;
        // 设置用于屏幕阅读器的 Emoji 面板的显示和隐藏状态
        $(ev.target).attr('aria-expanded', showEmoji);
        // 聚焦到 emoji 面板的第一个按钮
        $('#emoji-panel button').eq(0).focus();
        // 避免触发页面空白区域
        return false;
      });

      // 切换Emoji类型按钮点击
      $('#emoji-classification button').on('click', ev => {
        const emoji = emojiList[$(ev.target).attr('data-classification')];
        let emojiEl = '';

        // 清除之前选中的按钮的选中状态
        $('#emoji-classification .selected').attr('aria-checked', false);
        $('#emoji-classification .selected').removeClass('selected');
        // 设置点击按钮的选中状态
        $(ev.target).attr('aria-checked', true);
        $(ev.target).addClass('selected');

        // 生成 Emoji 元素
        emoji.forEach(e => {
          emojiEl += '<div class="emoji p-2" tabindex="0" role="listitem">' + e + '</div>';
        });

        // 清除之前的 Emoji
        if ($('#emoji-list .emoji').length) {
          $('#emoji-list .emoji').remove();
        }

        // 把 Emoji 插入到页面
        $('#emoji-list').append(emojiEl);
        // 设置类型标题
        $('#emoji-title').html($(ev.target).attr('title'));
        // 设置用于屏幕阅读器的表情列表标题
        $('#emoji-list').attr('aria-label', `${$(ev.target).attr('title')} ${window.t.pressEnterToAddTheEmojiToTheCommentInputField}`);
      });

      // Emoji 表情点击
      $('#emoji-list').on('click', '.emoji', ev => {
        // 把表情添加到评论内容输入框
        $('#textarea').val($('#textarea').val() + $(ev.target).html());
      });

      // Emoji 表情按下回车或 Tab
      $('#emoji-list').on('keydown', '.emoji', ev => {
        // 按下回车键
        if (ev.keyCode === 13) {
          // 把表情添加到评论内容输入框
          $('#textarea').val($('#textarea').val() + $(ev.target).html());
        }
        // 按下 Tab
        if (ev.keyCode === 9 && $(ev.target).is('#emoji-list .emoji:last-child')) {
          ev.preventDefault();
          // 聚焦到 emoji 面板的第一个按钮
          $('#emoji-panel button').eq(0).focus();
        }
      });

      // Emoji 表情面板按下 ESC
      $('#emoji-panel').on('keydown', ev => {
        if (ev.keyCode === 27) {
          // 调用 Emoji 开关事件
          $('#show-emoji-btn').click();
          $('#textarea').focus();
        }
      });

      // Emoji 面板的空白区域点击
      $('#emoji-panel').on('click', () => {
        return false;
      });
    }
  }

  // 点赞初始化
  function likeInit() {
    if ($('.agree-btn').length) {
      $('.agree-btn').on('click', () => {
        $('.agree-btn').get(0).disabled = true;
        $.ajax({
          type: 'post',
          url: $('.agree-btn').attr('data-url'),
          data: 'agree=' + $('.agree-btn').attr('data-cid'),
          async: true,
          timeout: 15000,
          cache: false,
          success: data => {
            const re = /\d/;
            if (!re.test(data)) return false;
            $('.agree-num').html(`${window.t.like} ${data}`);
            // 创建点赞提示的元素
            $('body').append(`<span id="agree-p" role="alert">${window.t.like} + 1</span>`);
            // 设置点赞提示的样式
            $('#agree-p').css({
              top: $('.agree-btn').offset().top - 25,
              left: $('.agree-btn').offset().left + $('.agree-btn').outerWidth() / 2 - $('#agree-p').outerWidth() / 2
            });
            // 让点赞提示消失
            $('#agree-p').animate({
              top: $('.agree-btn').offset().top - 70,
              opacity: 0
            }, 400, function () {
              $('#agree-p').remove();
            });
          },
          error: () => {
            $('.agree-btn').get(0).disabled = false;
          }
        });
      });
    }
  }

  // 头像样式初始化
  function avatarStyleInit() {
    // 头像加载完成后删除头像背景颜色
    if ($('.avatar').length) {
      $('.avatar').on('load', ev => {
        $(ev.target).css('background-color', 'none');
      });
    }

    // 给评论者头像添加错误事件
    for (let i = 0;i < $('.avatar').length;i ++) {
      // 检测是否是图片
      if ($('.avatar').eq(i)[0].tagName === 'IMG') {
        $('.avatar').eq(i).on('error', ev => {
          // 获取头像昵称
          const name = $(ev.target).attr('alt');
          // 创建文字头像元素
          const avatarEl = document.createElement('div');
          avatarEl.setAttribute('role', 'img');
          avatarEl.setAttribute('aria-label', name);
          // 设置文字头像的 class
          avatarEl.className = 'pingback avatar';
          // 把文字头像的内容设置为评论者昵称的第一个字
          avatarEl.innerHTML = name.substring(0, 1);

          // 检测是否重复出现
          const nameIndex = avatarName.indexOf(name);
          if (nameIndex === -1) {
            avatarName.push(name);
            // 生成随机颜色
            const bgColor = {r: rand(250, 1), g: rand(250, 1), b: rand(250, 1)};
            // 把颜色添加到数组，遇到同名的头像可以使用同一组颜色
            avatarColor.push(bgColor);
            // 设置文字头像的背景颜色
            avatarEl.style.background = 'rgb(' + bgColor.r + ',' + bgColor.g + ',' + bgColor.b + ')';
          }else {
            // 设置文字头像的背景颜色
            avatarEl.style.background = 'rgb(' + avatarColor[nameIndex].r + ',' + avatarColor[nameIndex].g + ',' + avatarColor[nameIndex].b + ')';
          }

          // 把文字头像插入到页面
          $(ev.target).before(avatarEl);
          // 移除加载失败的头像
          $(ev.target).remove();
        });
      }
    }

    // 给独立页友情链接的网站 Logo 添加加载错误事件
    $('.page-links .logo').on('error', ev => {
      // 创建默认网站 Logo
      const logoEl = '<div role="img" class="logo-icon mr-2"><i class="icon-link"></i></div>';
      // 把默认网站 Logo 插入到页面
      $(ev.target).before(logoEl);
      // 移除加载失败的网站 Logo
      $(ev.target).remove();
    });
  }

  // 生成随机数的函数
  function rand(max, min) {
    const num = max - min;
    return Math.round(Math.random() * num + min);
  }

  // 文章目录跳转样式
  function directoryStyleInit() {
    $('.directory-link').on('click', ev => {
      ev.preventDefault();
      const titleSelect = `[data-title="${$(ev.target).attr('data-directory')}"]`;
      $('html').animate({
        scrollTop: $(titleSelect).offset().top - 60
      }, 400);
      return false;
    });

    // 如果开启了移动设备文章目录就给目录添加事件
    if ($('#directory-mobile').length) {
      // 重置目录状态
      if (directory) directory = false;
      // 移动设备的目录按钮点击
      $('#directory-btn').on('click', () => {
        if (!directory) {
          $('#directory-mobile').css('display', 'flex');
          $('#directory-mobile').animate({opacity: 1}, 250);
          directory = true;
        }else {
          $('#directory-mobile').animate({opacity: 0}, 250, () => {
            $('#directory-mobile').hide();
          });
          directory = false;
        }
        $('#directory-btn').attr('aria-expanded', directory);
      });

      // 移动设备的关闭目录按钮点击
      $('#directory-mobile .close-btn').on('click', () => {
        $('#directory-btn').click();
      });
    }
  }

  // 调整侧边栏章节目录的尺寸
  function directorySize() {
    if ($('.sidebar .directory').length) {
      // 获取侧边栏章节目录的位置
      directoryTop = $('.sidebar .directory').offset().top;
      // 设置侧边栏章节目录的最大高度
      $('.sidebar .directory').css('max-height', window.innerHeight - 100);
      $('.sidebar .directory > .article-directory').css('width', $('.sidebar .directory').width());
    }
  }

// 分页链接初始化
  function paginationLinkInit() {
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

  // 图片灯箱初始化
  function imageLightboxInit() {
    let imgWH = '';  // 记录图片的宽高
    let imgDirection = 0;  // 图片方向
    let contentImgSize = null;  // 文章区域的图片尺寸

    $('.post-content img').on('click', ev => {
      // 如果图片还没有加载完成
      if ($(ev.target).attr('class') === 'load-img') return false;
      // 获取图片的真实尺寸
      const imgSize = {
        w: ev.target.naturalWidth,
        h: ev.target.naturalHeight
      };

      // 获取文章内的图片尺寸
      contentImgSize = {
        w: $(ev.target).width(),
        h: $(ev.target).height(),
        l: $(ev.target).offset().left,
        t: $(ev.target).offset().top
      };

      // 如果图片的真实尺寸超出屏幕尺寸就重新设置大图的尺寸
      if (imgSize.w > window.innerWidth) {
        imgSize.p = imgSize.h / imgSize.w * 100;
        imgSize.w = window.innerWidth;
        imgSize.h = imgSize.w * imgSize.p / 100;
      }
      if (imgSize.h > window.innerHeight) {
        imgSize.p = imgSize.w / imgSize.h * 100;
        imgSize.h = window.innerHeight;
        imgSize.w = imgSize.h * imgSize.p / 100;
      }

      // 图片灯箱HTML
      const maxImgTemplate = `
      <div id="max-img-box" role="dialog" aria-modal="true" aria-labelledby="img-info">
        <div id="max-img-bg"></div>
        <div class="btn-group max-img-features-btn">
          <button type="button" class="btn big" aria-label="${window.t.zoomIn}" title="${window.t.zoomIn}">
            <i class="icon-zoom-in"></i>
          </button>
          <button type="button" class="btn small" aria-label="${window.t.zoomOut}" title="${window.t.zoomOut}">
            <i class="icon-zoom-out"></i>
          </button>
          <button type="button" class="btn spin-left" aria-label="${window.t.rotateLeft}" title="${window.t.rotateLeft}">
            <i class="icon-undo"></i>
          </button>
          <button type="button" class="btn spin-right" aria-label="${window.t.rotateRight}" title="${window.t.rotateRight}">
            <i class="icon-redo"></i>
          </button>
          <button type="button" class="btn hide-img" aria-label="${window.t.closeImage}" title="${window.t.closeImage}">
            <i class="icon-cancel-circle"></i>
          </button>
        </div>
        <img src="" alt="大图" class="shadow" id="max-img">
        <p id="img-info" class="text-light text-center"></p>
      </div>
      `;
      // 把图片灯箱插入到页面
      $('body').append(maxImgTemplate);

      // 显示大图
      $('#max-img-box').show();
      // 显示半透明背景
      $('#max-img-bg').fadeIn(250);
      // 设置大图的初始尺寸和位置
      $('#max-img').css({
        display: 'inline',
        width: contentImgSize.w,
        height: contentImgSize.h,
        top: contentImgSize.t,
        left: contentImgSize.l
      });
      // 把大图移动到屏幕中心
      $('#max-img').animate({
        width: imgSize.w,
        height: imgSize.h,
        left: window.innerWidth / 2 - imgSize.w / 2,
        top: $(document).scrollTop() + window.innerHeight / 2 - imgSize.h / 2
      }, 250, 'linear', () => {
        // 显示图片操作按钮
        $('.max-img-features-btn').css('display', 'flex');
        // 让关闭图片的按钮获取焦点
        $('.max-img-features-btn .hide-img').focus();
        // 显示和设置图片描述
        $('#img-info').show();
        $('#img-info').html($(ev.target).attr('alt'));
        // 把图片灯箱的状态设置为开启
        maxImg = true;
      });
      // 设置大图的 src 和 alt
      $('#max-img').attr({
        src: $(ev.target).attr('src'),
        alt: $(ev.target).attr('alt')
      });
      // 把图片角度设置为默认
      if (imgDirection !== 0) {
        imgDirection = 0;
        $('#max-img').css('transform', 'rotate(' + imgDirection + 'deg)');
      }
      // 禁止滚动
      $('html').addClass('stop-scrolling');

      // 给图片灯箱添加事件
      // 在图片灯箱开启的情况下滑动屏幕禁止页面滚动
      $('#max-img-bg, .max-img-features-btn, #img-info').on('touchmove', ev => {
        if (maxImg) {
          ev.preventDefault();
          return false;
        }
      });

      // 大图手指拖拽
      $('#max-img').on('touchstart', ev => {
        const X = ev.touches[0].pageX - $(ev.target).get(0).offsetLeft;
        const Y = ev.touches[0].pageY - $(ev.target).get(0).offsetTop;

        $(document).on('touchmove', ev => {
          $('#max-img').css({
            left: ev.touches[0].pageX - X,
            top: ev.touches[0].pageY - Y
          });
        });

        $(document).on('touchend', () => {
          $(document).off('touchmove');
        });
        return false;
      });

      // 大图拖拽
      $('#max-img').on('mousedown',  ev => {
        const X = ev.clientX - $(ev.target).get(0).offsetLeft;
        const Y = ev.clientY - $(ev.target).get(0).offsetTop;

        $(document).on('mousemove', ev => {
          $('#max-img').css({
            left: ev.clientX - X,
            top: ev.clientY - Y
          });
        });

        $(document).on('mouseup', ev => {
          $(document).off('mousemove');
        });
        return false;
      });

      // 大图左旋转
      $('.max-img-features-btn .spin-left').on('click', () => {
        imgDirection -= 90;
        $('#max-img').css('transition', '0.3s');
        $('#max-img').css('transform', `rotate(${imgDirection}deg)`);
        setTimeout(function () {
          $('#max-img').css('transition', '0s');
        }, 300);
      });

      // 大图右旋转
      $('.max-img-features-btn .spin-right').on('click', () => {
        imgDirection += 90;
        $('#max-img').css('transition', '0.3s');
        $('#max-img').css('transform', `rotate(${imgDirection}deg)`);
        setTimeout(function () {
          $('#max-img').css('transition', '0s');
        }, 300);
      });

      // 图片放大
      $('.max-img-features-btn .big').on('click',  () => {
        $('#max-img').animate({
          width: $('#max-img').width() + $('#max-img').width() / 5,
          height: $('#max-img').height() + $('#max-img').height() / 5
        }, 250);
      });

      // 图片缩小
      $('.max-img-features-btn .small').on('click', () => {
        // 如果图片的宽度或高度 < 40px 将不再缩小
        if ($('#max-img').width() <= 80 || $('#max-img').height() <= 80) return false;
        $('#max-img').animate({
          width: $('#max-img').width() - $('#max-img').width() / 5,
          height: $('#max-img').height() - $('#max-img').height() / 5
        }, 250);
      });

      // 大图鼠标滚动
      $('#max-img').on('mousewheel DOMMouseScroll', ev => {
        if (!maxImg) return false;
        if (ev.originalEvent.wheelDelta === undefined) return false;
        if (ev.originalEvent.wheelDelta >  0) {
          // 放大图片
          $('.max-img-features-btn .big').click();
        }else {
          // 缩小图片
          $('.max-img-features-btn .small').click();
        }
      });

      // 大图的关闭按钮点击
      $('.max-img-features-btn .hide-img').on('click', () => {
        maxImg = false;
        // 隐藏半透明背景
        $('#max-img-bg').fadeOut(250);
        // 隐藏图片描述
        $('#img-info').hide();
        // 隐藏图片功能区按钮
        $('.max-img-features-btn').hide();
        $('html').removeClass('stop-scrolling');
        $('#max-img').animate({
          width: contentImgSize.w,
          height: contentImgSize.h,
          top: contentImgSize.t,
          left: contentImgSize.l
        }, 250, 'linear', () => {
          $('#max-img').hide();
          $('#max-img').attr({
            src: '',
            alt: ''
          });
          $('#max-img-box').hide();
          $('#max-img-box').remove();
        });
      });

      // 关闭大图按钮按下 tab
      $('.max-img-features-btn .hide-img').on('keydown', ev => {
        ev.preventDefault();
        if (ev.keyCode === 9) {
          // 让放大图片按钮获取焦点
          $('.max-img-features-btn .big').focus();
        }
        if (ev.keyCode === 13) {
          $('.max-img-features-btn .hide-img').click();
        }
      });
    });
  }

// 表格初始化
  function tableInit() {
    if ($('.post-content table').length) {
      for (var i = 0; i < $('.post-content table').length; i++) {
        //  生成 Bootstrap 的响应式表格
        const table = '<div class="table-responsive"><table class="table table-striped table-bordered table-hover">' + $('.post-content table').eq(i).html() + '</table></div>';
        $('.post-content table').eq(i).replaceWith(table);  //  替换文章中的表格
      }
    }
  }

  // 代码高亮初始化
  function codeHighlightInit() {
    const codeLineNum = $('.post-content').attr('data-code-line-num');

    if ($('.enable-highlight').length && $('pre').length) {
      for (let i = 0;i < $('pre').length;i ++) {
        // 是否是代码块
        if ($('pre').eq(i).children('code').length) {
          // 添加代码高亮样式
          hljs.highlightBlock($('pre code').eq(i).get(0));

          // 给代码块添加行号
          if ($('.line-num-show').length) {
            // 获取代码行数
            const lineCount = $('pre code').eq(i).html().split(/\r\n|\r|\n/).length;
            let lineNumbersEl = '';
            for (let j = 0;j < lineCount;j ++) {
              lineNumbersEl += `<div class="text-right">${j + 1}</div>`;
            }
            $('pre').eq(i).prepend(`<div class="line-box">${lineNumbersEl}</div>`);
          }

          // 创建和添加拷贝按钮
          const btnEl = document.createElement('button');
          btnEl.className = 'copy-code-btn btn btn-outline-secondary btn-sm';
          btnEl.setAttribute('type', 'button');
          btnEl.innerHTML = '<i class="icon-copy"></i>';
          btnEl.setAttribute('aria-label', window.t.copyCode);
          btnEl.setAttribute('data-clipboard-target', '#code-' + i);
          btnEl.setAttribute('title', window.t.copyCode);
          btnEl.setAttribute('data-toggle', 'tooltip');
          btnEl.setAttribute('data-placement', 'left');
          btnEl.setAttribute('id', 'copy-btn-' + i);
          $('pre').eq(i).prepend(btnEl);
          // 给代码块添加一个 id 方便拷贝
          $('pre code').eq(i).attr('id', 'code-' + i);
        }
        // 初始化拷贝模块
        const clipboard = new ClipboardJS('.copy-code-btn');
        // 拷贝成功
        clipboard.on('success', ev => {
          // 把工具提示更改为拷贝成功
          $(ev.trigger).attr('title', window.t.copySuccess);
          $(ev.trigger).attr('data-original-title', window.t.copySuccess);
          $(ev.trigger).tooltip('update');
          $(ev.trigger).tooltip('show');
          // 延迟 1 秒后把工具提示更改为拷贝代码
          setTimeout(() => {
            $(ev.trigger).attr('title', window.t.copyCode);
            $(ev.trigger).attr('data-original-title', window.t.copyCode);
          }, 1000);
        });
        // 拷贝出错
        clipboard.on('error', ev => {
          $(ev.trigger).attr('title', window.t.copyError);
          $(ev.trigger).attr('data-original-title', window.t.copyError);
          $(ev.trigger).tooltip('hide');
          $(ev.trigger).tooltip('show');
          setTimeout(() => {
            $(ev.trigger).attr('title', window.t.copyCode);
            $(ev.trigger).attr('data-original-title', window.t.copyCode);
          }, 1000);
        });
      }
    }
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