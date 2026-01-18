/*!
* HomePage: https://www.misterma.com
* GithubPage: https://github.com/changbin1997
* ProjectPage: https://github.com/changbin1997/Facile
* author: Changbin (changbin1997)
* Licensed under MIT
*/

export default () => {
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