export default class AvatarGenerator {
  /**
   * 创建 AvatarGenerator 实例
   */
  constructor() {
    this.init();
    this.linksLogo();
  }

  /**
   * 初始化所有头像元素的事件监听
   * 为头像图片绑定加载失败和加载成功事件处理器
   * @returns {void}
   */
  init() {
    $('.avatar').each((index, img) => {
      const $img = $(img);
      
      // 监听图片加载错误事件
      $img.on('error', () => {
        this.generateTextAvatar($img);
      });

      // 监听图片加载成功事件，移除背景色
      $img.on('load', () => {
        $img.css('background-color', '');
      });

      // 检查图片是否已经加载失败（针对页面加载时就失败的情况）
      if (img.complete && img.naturalHeight === 0) {
        this.generateTextAvatar($img);
      }
    });
  }

  /**
   * 生成文字头像
   * @param {jQuery} $img - 图片元素
   */
  generateTextAvatar($img) {
    const username = $img.attr('alt') || '';
    const firstChar = username.charAt(0) || '?';
    const backgroundColor = this.generateColor(username);

    // 创建文字头像 div
    const $textAvatar = $('<div></div>')
      .addClass('avatar text-avatar')
      .attr('role', 'img')
      .attr('aria-label', username)
      .css('background-color', backgroundColor)
      .text(firstChar);

    // 替换原有的 img 元素
    $img.replaceWith($textAvatar);
  }

  /**
   * 根据用户名生成确定的背景颜色
   * @param {string} username - 用户名
   * @returns {string} - 颜色值（HSL格式）
   */
  generateColor(username) {
    // 计算用户名的哈希值
    let hash = 0;
    for (let i = 0; i < username.length; i++) {
      hash = username.charCodeAt(i) + ((hash << 5) - hash);
      hash = hash & hash; // 转换为32位整数
    }

    // 使用 HSL 颜色空间生成颜色
    // 色相（Hue）: 0-360度，根据哈希值确定
    const hue = Math.abs(hash % 360);
    
    // 饱和度（Saturation）: 固定在65-85%之间，保证颜色鲜艳
    const saturation = 65 + (Math.abs(hash) % 21);
    
    // 亮度（Lightness）: 固定在35-50%之间，保证颜色足够深，文字清晰可见
    const lightness = 35 + (Math.abs(hash >> 8) % 16);

    return `hsl(${hue}, ${saturation}%, ${lightness}%)`;
  }

  /**
   * 刷新所有头像（如果需要动态添加新头像时调用）
   */
  refresh() {
    this.init();
    this.linksLogo();
  }

  /**
   * 为加载失败的链接 logo 设置默认 logo
   * @returns 
   */
  linksLogo() {
    // 没有链接就直接返回
    if ($('img.logo').length < 1) return false;
    // 把加载失败的链接LOGO图片替换为字体图标
    $('img.logo').on('error', ev => {
      const linkLogo = `
      <div class="logo-icon mr-2" role="img" aria-label="${$(ev.target).attr('alt')}">
        <i class="icon-link"></i>
      </div>
      `;
      $(ev.target).after(linkLogo);
      $(ev.target).remove();
    });
    // 加载完成就删除默认的背景颜色
    $('img.logo').on('load', ev => {
      $(ev.target).css('background', 'none');
    });
  }
}