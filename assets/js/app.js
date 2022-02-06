hljs.initHighlightingOnLoad();  //  代码高亮

$(function () {
  let imgWH = '';  // 记录图片的宽高
  let imgDirection = 0;  // 图片方向
  let  maxImg = false;  // 是否开启图片灯箱
  let emojiList = null;  // Emoji 列表
  let showEmoji = false;  // Emoji 面板状态
  const avatarColor = [];  // 存储文字头像颜色
  const avatarName = [];  // 存储文字头像名称

  // 给分页链接添加class和aria属性
  if ($('.pagination li').length) {
    $('.pagination li').addClass('page-item');
    $('.pagination li a').addClass('page-link');
    $('.pagination .active a').attr('aria-current', 'page');
    if ($('.pagination .prev').length) {
      $('.pagination .prev a').attr({
        'aria-label': '上一页（左光标键）',
        'title': '上一页（左光标键）',
        'data-toggle': 'tooltip',
        'data-placement': 'top'
      });
    }
    if ($('.pagination .next').length) {
      $('.pagination .next a').attr({
        'aria-label': '下一页（右光标键）',
        'title': '下一页（右光标键）',
        'data-toggle': 'tooltip',
        'data-placement': 'top'
      });
    }
  }else {
    $('.page-nav').remove();
  }

  // 头像加载完成后删除头像背景颜色
  if ($('.avatar').length) {
    $('.avatar').on('load', ev => {
      $(ev.target).css('background-color', 'none');
    });
  }

  // 给文章中的表格添加Bootstrap的样式
  if ($('.post-content table').length) {
    for (var i = 0; i < $('.post-content table').length; i++) {
      //  生成 Bootstrap 的响应式表格
      const table = '<div class="table-responsive"><table class="table table-striped table-bordered table-hover">' + $('.post-content table').eq(i).html() + '</table></div>';
      $('.post-content table').eq(i).replaceWith(table);  //  替换文章中的表格
    }
  }

  // 给文章中的标签添加Bootstrap的样式
  if ($('.post-tag a').length) {
    $('.post-tag a').addClass('badge badge-dark');
  }

  // 生成二维码
  if ($('#qr') !== undefined) {
    const qr = new QRious({
      element: $('#qr').get(0),
      value: location.href,
      size: 150
    });
  }

  // 给文章内的图片添加点击事件
  $('.post-content img').on('click', ev => {
    // 获取图片的真实尺寸
    const imgSize = {
      w: ev.target.naturalWidth,
      h: ev.target.naturalHeight
    };
    // 根据图片真是尺寸设置大图的尺寸
    if (imgSize.w > imgSize.h) {
      if (imgSize.w >= $(window).width()) {
        $('#max-img-box img').css('width', '90%');
      } else {
        $('#max-img-box img').css('width', imgSize.w);
      }
      $('#max-img-box img').css('height', 'auto');
      imgWH = 'width';
    } else {
      if (imgSize.h >= $(window).height()) {
        $('#max-img-box img').css('height', '90%');
      } else {
        $('#max-img-box img').css('height', imgSize.h);
      }
      $('#max-img-box img').css('width', 'auto');
      imgWH = 'height';
    }
    // 显示大图
    $('#max-img-box').fadeIn(250);
    // 设置大图的 src 和 alt
    $('#max-img-box img').attr({
      src: $(ev.target).attr('src'),
      alt: $(ev.target).attr('alt')
    });
    // 调整图片方向
    if (imgDirection !== 0) {
      imgDirection = 0;
      $('#max-img-box img').css('transform', 'rotate(' + imgDirection + 'deg)');
    }
    // 让图片居中
    $('#max-img-box img').css({
      left: $(window).width() / 2 - $('#max-img-box img').width() / 2,
      top: $(window).height() / 2 - $('#max-img-box img').height() / 2
    });
    // 禁止滚动
    $('html').addClass('stop-scrolling');
    // 让关闭图片的按钮获取焦点
    $('.hide-img').focus();
    // 设置图片灯箱为开启状态
    maxImg = true;
    return false;
  });

  // 大图手指拖拽
  $('#max-img-box img').on('touchstart', ev => {
    const X = ev.touches[0].pageX - $(ev.target).get(0).offsetLeft;
    const Y = ev.touches[0].pageY - $(ev.target).get(0).offsetTop;

    $(document).on('touchmove', ev => {
      $('#max-img-box img').css({
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
  $('#max-img-box img').on('mousedown',  ev => {
    const X = ev.clientX - $(ev.target).get(0).offsetLeft;
    const Y = ev.clientY - $(ev.target).get(0).offsetTop;

    $(document).on('mousemove', ev => {
      $('#max-img-box img').css({
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
  $('#max-img-box .spin-left').on('click', () => {
    imgDirection -= 90;
    $('#max-img-box img').css('transition', '0.3s');
    $('#max-img-box img').css('transform', 'rotate(' + imgDirection + 'deg)');
    setTimeout(function () {
      $('#max-img-box img').css('transition', '0s');
    }, 300);
  });

  // 大图右旋转
  $('#max-img-box .spin-right').on('click', () => {
    imgDirection += 90;
    $('#max-img-box img').css('transition', '0.3s');
    $('#max-img-box img').css('transform', 'rotate(' + imgDirection + 'deg)');
    setTimeout(function () {
      $('#max-img-box img').css('transition', '0s');
    }, 300);
  });

  // 图片放大
  $('#max-img-box .big').on('click',  () => {
    const size = imgWH === 'width' ? $('#max-img-box img').width() + 40 : $('#max-img-box img').height() + 40;
    $('#max-img-box img').css('transition', '0.2s');
    $('#max-img-box img').css(imgWH, size + 'px');
    setTimeout(function () {
      $('#max-img-box img').css('transition', '0s');
    }, 300);
  });

  // 图片缩小
  $('#max-img-box .small').on('click', () => {
    const size = imgWH === 'width' ? $('#max-img-box img').width() - 40 : $('#max-img-box img').height() - 40;
    // 如果图片的宽度或高度 < 40px 将不再缩小
    if ($('#max-img-box img').width() <= 40 || $('#max-img-box img').height() <= 40) return false;
    $('#max-img-box img').css('transition', '0.2s');
    $('#max-img-box img').css(imgWH, size + 'px');
    setTimeout(function () {
      $('#max-img-box img').css('transition', '0s');
    }, 300);
  });

  // 大图的关闭按钮点击
  $('#max-img-box .hide-img').on('click', () => {
    $('#max-img-box').fadeOut(250, () => {
      $('#max-img-box img').attr('src', '');
    });
    maxImg = false;
    $('html').removeClass('stop-scrolling');
  });

  // 关闭大图按钮按下 tab
  $('#max-img-box .hide-img').on('keydown', ev => {
    ev.preventDefault();
    if (ev.keyCode === 9) {
      // 让放大图片按钮获取焦点
      $('#max-img-box .big').focus();
    }
    if (ev.keyCode === 13) {
      $('#max-img-box .hide-img').click();
    }
  });

  // 全局快捷键
  $(document).on('keyup', ev => {
    // 如果是 ESC 就关闭大图
    if (ev.keyCode === 27 && maxImg) {
      $('#max-img-box .hide-img').click();
    }
    // 如果按下的是 + 就放大图片
    if (ev.keyCode === 107 && maxImg) {
      $('#max-img-box .big').click();
    }
    // 如果按下的是 - 就缩小图片
    if (ev.keyCode === 109 && maxImg) {
      $('#max-img-box .small').click();
    }
    // 如果按下的是右方向键就跳转到下一页
    if (ev.keyCode === 39 && $('.next .page-link').length) {
      location.href = $('.next .page-link').attr('href');
    }
    // 如果按下的是左方向键就跳转到上一页
    if (ev.keyCode === 37 && $('.prev .page-link').length) {
      location.href = $('.prev .page-link').attr('href');
    }
  });

  // 给文章内的链接添加 target 属性
  if ($('.post-page .post-content a').length) {
    $('.post-content a').attr('target', '_blank');
  }

  // 给评论区的评论者链接添加 target 属性
  if ($('.comment-info .author a').length) {
    $('.comment-info .author a').attr('target', '_blank');
  }

  // 回复对象名字鼠标移入和移出
  $('#comments .parent').hover(ev => {
    $($(ev.target).attr('href') + ' .comment-content').css({
      background: '#D0210E',
      color: '#FFFFFF'
    });
  }, ev => {
    $($(ev.target).attr('href') + ' .comment-content').css({
      background: 'none',
      color: '#212529'
    });
  });

  // 评论回复链接鼠标移入移出
  $('#comments .comment-reply').hover(ev => {
    const cid = $(ev.target).parent().attr('data-id');
    //alert(cid);
    $('#' + cid + ' .comment-content').css({
      background: '#D0210E',
      color: '#FFFFFF'
    });
  }, ev => {
    const cid = $(ev.target).parent().attr('data-id');
    $('#' + cid + ' .comment-content').css({
      background: 'none',
      color: '#212529'
    });
  });

  // 点赞
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
        $('.agree-num').html('赞 ' + data);
        // 创建点赞提示的元素
        $('body').append('<span id="agree-p" role="alert">赞 + 1</span>');
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

  // 初始化气球提示
  $('[data-toggle="tooltip"]').tooltip();

  // 加载 Emoji
  if ($('#emoji-panel').length) {
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
  }

  // Emoji 开关点击
  $('#show-emoji-btn').on('click', ev => {
    // 设置 Emoji 面板的显示和隐藏
    $('#emoji-panel').slideToggle(250);
    // 设置 Emoji 的显示和隐藏状态
    showEmoji = !showEmoji;
    // 设置用于屏幕阅读器的 Emoji 面板的显示和隐藏状态
    $(ev.target).attr('aria-expanded', showEmoji);
    return false;
  });

  // 页面空白区域点击
  $('body').on('click', () => {
    // 如果表情面板处于开启状态就关闭表情面板
    if (showEmoji) $('#show-emoji-btn').click();
  });

  // Emoji 面板的空白区域点击
  $('#emoji-panel').on('click', () => {
    return false;
  });

  // 评论内容输入框点击
  $('#textarea').on('click', () => {
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
    $('#emoji-list').attr('aria-label', $(ev.target).attr('title') + '（按回车可以把表情添加到评论内容输入框）');
  });

  // Emoji 表情点击
  $('#emoji-list').on('click', '.emoji', ev => {
    // 把表情添加到评论内容输入框
    $('#textarea').val($('#textarea').val() + $(ev.target).html());
  });

  // Emoji 表情按下回车键
  $('#emoji-list').on('keypress', '.emoji', ev => {
    if (ev.keyCode === 13) {
      // 把表情添加到评论内容输入框
      $('#textarea').val($('#textarea').val() + $(ev.target).html());
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

  // 给独立页友情链接的网站 Logo 添加加载错误事件
  $('.page-links .logo').on('error', ev => {
    // 创建默认网站 Logo
    const logoEl = '<div role="img" class="logo-icon mr-2"><i class="icon-link"></i></div>';
    // 把默认网站 Logo 插入到页面
    $(ev.target).before(logoEl);
    // 移除加载失败的网站 Logo
    $(ev.target).remove();
  });

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

  // 生成随机数的函数
  function rand(max, min) {
    const num = max - min;
    return Math.round(Math.random() * num + min);
  }

  // 切换主题的单选框改变
  $('.change-theme-color').on('click', ev => {
    // 获取选中的颜色
    const color = $(ev.target).attr('id');
    // 获取当前的时间戳
    let time = Date.parse(new Date());
    // 在当前的时间戳上 + 180天
    time += 15552000000;
    time = new Date(time);
    // 写入 cookie
    document.cookie = 'themeColor=' + color + ';path=/;expires=Tue,' + time;
    // 刷新网页
    location.reload();
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
});