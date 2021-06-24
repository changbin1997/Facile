hljs.initHighlightingOnLoad();  //  代码高亮

$(function () {
  let imgWH = '';  // 记录图片的宽高
  let imgDirection = 0;  // 图片方向
  let  maxImg = false;  // 是否开启图片灯箱

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

  // 文章图片点击
  $('.post-content img').on('click', ev => {
    // 获取图片的真实尺寸
    const imgSize = {
      w: ev.toElement.naturalWidth,
      h: ev.toElement.naturalHeight
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
    const X = ev.touches[0].pageX - $(ev.target).offset().left;
    const Y = ev.touches[0].pageY - $(ev.target).offset().top;

    $(document).on('touchmove', ev => {
      $('#max-img-box img').css({
        left: ev.touches[0].pageX - X,
        top: ev.touches[0].pageY - Y - $(document).scrollTop()
      });
    });

    $(document).on('touchend', () => {
      $(document).off('touchmove');
    });
    return false;
  });

  // 大图拖拽
  $('#max-img-box img').on('mousedown',  ev => {
    const X = ev.clientX - $(ev.target).offset().left;
    const Y = ev.clientY - $(ev.target).offset().top;

    $(document).on('mousemove', ev => {
      $('#max-img-box img').css({
        left: ev.clientX - X,
        top: ev.clientY - Y - $(document).scrollTop()
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

  $('[data-toggle="tooltip"]').tooltip();
});