/*!
* HomePage: https://www.misterma.com
* GithubPage: https://github.com/changbin1997
* ProjectPage: https://github.com/changbin1997/Facile
* author: Changbin (changbin1997)
* Licensed under MIT
*/

export default class ArticleEngagement {
  /**
   * 生成分享二维码
   */
  static shareQrCode() {
    if ($('#qr') !== undefined) {
      const qr = new QRious({
        element: $('#qr').get(0),
        value: location.href,
        size: 150
      });
    }
  }

  /**
   * 点赞初始化
   */
  static likeInit() {
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
}