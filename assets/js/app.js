/*!
* HomePage: https://www.misterma.com
* GithubPage: https://github.com/changbin1997
* ProjectPage: https://github.com/changbin1997/Facile
* author: Mr. Ma
* Licensed under MIT
*/

$(function () {
  let imgWH = '';  // 记录图片的宽高
  let imgDirection = 0;  // 图片方向
  let maxImg = false;  // 是否开启图片灯箱
  let contentImgSize = null;  // 文章区域的图片尺寸
  let emojiList = null;  // Emoji 列表
  let showEmoji = false;  // Emoji 面板状态
  const codeLineNum = $('.post-content').attr('data-code-line-num');
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

  // 给文章中的代码块添加拷贝按钮和拷贝事件
  if ($('pre').length) {
    for (let i = 0;i < $('pre').length;i ++) {
      // 是否是代码块
      if ($('pre').eq(i).children('code').length) {
        // 给代码块添加行号
        if (codeLineNum === 'show') {
          $('pre code').eq(i).css('padding-left', '54px');
          const lineNumbers = Math.floor($('pre code').eq(i).height() / 24);
          let lineNumbersEl = '';
          for (let j = 0;j < lineNumbers;j ++) {
            lineNumbersEl += `<div class="text-right">${j + 1}</div>`;
          }
          $('pre').eq(i).prepend(`<div class="line-box">${lineNumbersEl}</div>`);
        }

        // 创建和添加拷贝按钮
        const btnEl = document.createElement('button');
        btnEl.className = 'copy-code-btn btn btn-outline-secondary btn-sm';
        btnEl.setAttribute('type', 'button');
        btnEl.innerHTML = '<i class="icon-copy"></i>';
        btnEl.setAttribute('aria-label', '拷贝代码');
        btnEl.setAttribute('data-clipboard-target', '#code-' + i);
        btnEl.setAttribute('title', '拷贝代码');
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
        $(ev.trigger).attr('title', '拷贝成功');
        $(ev.trigger).attr('data-original-title', '拷贝成功');
        $(ev.trigger).tooltip('update');
        $(ev.trigger).tooltip('show');
        // 延迟 1 秒后把工具提示更改为拷贝代码
        setTimeout(() => {
          $(ev.trigger).attr('title', '拷贝代码');
          $(ev.trigger).attr('data-original-title', '拷贝代码');
        }, 1000);
      });
      // 拷贝出错
      clipboard.on('error', ev => {
        $(ev.trigger).attr('title', '拷贝失败');
        $(ev.trigger).attr('data-original-title', '拷贝失败');
        $(ev.trigger).tooltip('hide');
        $(ev.trigger).tooltip('show');
        setTimeout(() => {
          $(ev.trigger).attr('title', '拷贝代码');
          $(ev.trigger).attr('data-original-title', '拷贝代码');
        }, 1000);
      });
    }
  }

  // 给文章中的标签添加Bootstrap的样式
  if ($('.post-tag a').length) {
    $('.post-tag a').addClass('badge badge-dark');
  }

  // 文章是否加密
  if ($('.post-content .protected').length) {
    // 替换 Typecho 默认的密码输入表单
    $('.protected .word').replaceWith('<h2 class="word text-center mb-4">请输入密码访问</h2>');
    const formEl = `
    <div class="row">
      <div class="col-xl-8 col-lg-10 col-md-8 col-sm-12 col-12 offset-xl-2 offset-lg-1 offset-md-2">
        <div class="input-group mb-4">
          <input type="password" placeholder="请在此处输入密码" name="protectPassword" class="text form-control" required autofocus>
          <div class="input-group-append">
            <button type="submit" class="btn btn-primary submit">提交密码</button>
          </div>
        </div>
      </div>
    </div>
    `;
    $('.protected p').replaceWith(formEl);
  }

  // 文章内的章节目录跳转
  $('.directory-link').on('click', ev => {
    ev.preventDefault();
    const titleSelect = `[data-title="${$(ev.target).attr('data-directory')}"]`;
    $('html').animate({
      scrollTop: $(titleSelect).offset().top - 60
    }, 400);
    return false;
  });

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

    // 显示大图
    $('#max-img-box').fadeIn(250);
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
    return false;
  });

  // 在图片灯箱开启的情况下滑动屏幕禁止页面滚动
  $('#max-img-box, .max-img-features-btn, #img-info').on('touchmove', ev => {
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
    $('#max-img-box').fadeOut(250);
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

  // 全局快捷键
  $(document).on('keyup', ev => {
    // 如果是 ESC 就关闭大图
    if (ev.keyCode === 27 && maxImg) {
      $('.max-img-features-btn .hide-img').click();
    }
    // 如果按下的是 + 就放大图片
    if (ev.keyCode === 107 && maxImg) {
      $('.max-img-features-btn .big').click();
    }
    // 如果按下的是 - 就缩小图片
    if (ev.keyCode === 109 && maxImg) {
      $('.max-img-features-btn .small').click();
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

  if ($('.comments-lists > ol').length) {
    $('.comments-lists > ol').attr('aria-label', '评论区');
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

  // 评论回复链接获取焦点
  $('#comments .comment-reply a').on('focus', ev => {
    const cid = $(ev.target).parent().attr('data-id');
    $('#' + cid + ' .comment-content').css({
      background: '#D0210E',
      color: '#FFFFFF'
    });
  });

  // 评论列表的回复链接点击
  $('.comment-reply').on('click', () => {
    if ($('.comment-list .comment-input').length && $('#cancel-comment-reply-link').length) {
      $('#cancel-comment-reply-link').addClass('btn btn-outline-primary ml-2');
      $('#cancel-comment-reply-link').attr('role', 'button');
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

  // 评论回复链接失去焦点
  $('#comments .comment-reply a').on('blur', ev => {
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

  // 根据当前使用的主题配色来设置侧边栏主题单选框的选中状态
  if ($('.change-color').length) {
    // 浅色模式
    if ($('.light-color').length) $('#light-color').attr('checked', true);
    // 深色模式
    if ($('.dark-color').length) $('#dark-color').attr('checked', true);
    // 如果使用了跟随系统主题就检测系统主题的配色模式
    if ($('.auto-color').length) {
      const darkColor = window.matchMedia('(prefers-color-scheme: dark)');
      if (darkColor.matches) {
        // 深色
        $('#dark-color').attr('checked', true);
      }else {
        // 浅色
        $('#light-color').attr('checked', true);
      }
    }
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
    // 更改配色
    $('body').removeClass($('body').attr('data-color'));
    $('body').addClass(color);
    $('body').attr('data-color', color);
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
// 代码高亮初始化
hljs.initHighlightingOnLoad();