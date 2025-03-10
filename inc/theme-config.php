<?php

// 主题设置
function themeConfig($form) {
    echo <<<EOT
    <p>您现在使用的是 Facile 的开发板，开发板暂无版本号。<a href="https://github.com/changbin1997/Facile/releases" target="_blank">点击查看发行版</a></p>
    <p>主题使用帮助 <a href="https://facile.misterma.com/" target="_blank">点击查看帮助文档</a> ，遇到问题也可以到 <a href="https://www.misterma.com/msg.html" target="_blank">留言板</a> 或 <a href="https://www.misterma.com/archives/899/" target="_blank">Facile 介绍页</a> 留言。因为我有两个主题，为了更高效的解决问题，建议到 <a href="https://www.misterma.com/archives/899/" target="_blank">Facile 介绍页</a> 留言。</p>
    <button aria-describedby="export-description" id="export-btn" type="button" class="btn">导出主题配置文件</button>
    <button aria-describedby="export-description" id="import-btn" type="button" class="btn">导入主题配置文件</button>
    <a href="javascript:;" id="download-file" style="display: none;">下载</a>
    <input type="file" id="file-select" style="display: none;">
    <p id="export-description"><b>导出主题配置文件</b> 可以把主题外观设置导出为 JSON 文件，主要用来备份主题设置，<b>导入主题配置文件</b> 可以导入 <b>Facile</b> 主题的 JSON 配置文件。Typecho 切换主题的时候会清空主题设置，为了避免重复设置，在切换主题之前可以先导出主题设置配置。
    <div id="options-list">
        <h3>选项目录</h3>
        <ul aria-label="选项目录 - 点击可快速滚动到对应的选项分组"></ul>
        <button class="btn primary submit-options" type="button">保存设置</button>
    </div>
EOT;
    echo '<script type="text/javascript">';
    require_once __DIR__ . '/../assets/js/options-panel.js';
    echo '</script>';
    echo '<style type="text/css">';
    require_once __DIR__ . '/../assets/css/options-panel.css';
    echo '</style>';
    require_once __DIR__ . '/../components/link-editor.php';

    // 语言
    $form->addInput(new Typecho_Widget_Helper_Form_Element_Radio('language', array(
        'zh-CN' => '简体中文',
        'en' => 'English',
        'auto' => '自动选择语言'
    ), 'zh-CN', _t('默认显示的语言'), _t('自动选择语言会根据 HTTP 发送的语言偏好来选择语言，如果用户的语言偏好不是主题支持的语言，或者 HTTP 请求不包含语言偏好，默认选择英文。你还可以开启顶部导航栏的语言切换按钮或侧边栏添加一个语言选择组件来让用户手动更改语言，用户选择的语言会通过 Cookie 保存到用户的浏览器，下次访问时就会使用用户设置的语言。')));

    // 语言切换按钮
    $form->addInput(new Typecho_Widget_Helper_Form_Element_Radio('changeLanguageBtn', array(
        'show' => '启用',
        'hide' => '禁用',
    ), 'show', _t('语言切换按钮'), _t('语言切换按钮会显示在顶部导航栏的搜索区域左侧，用户可以手动更改语言。')));

    // 主题配色
    $form->addInput(new Typecho_Widget_Helper_Form_Element_Radio('themeColor', array(
        'light-color' => '浅色主题',
        'dark-color' => '深色主题',
        'auto-color' => '跟随系统主题'
    ), 'auto-color', _t('默认主题配色'), _t('主题配色会优先使用访问者设置的配色，如果访问者没有更改过配色就会使用默认设置。主题配色设置组件可以在侧边栏组件设置中添加或删除。')));

    //  站点Logo
    $form->addInput(new Typecho_Widget_Helper_Form_Element_Text('logoUrl', null, null, _t('站点 Logo icon 地址'), _t('Logo 是一个 ico 格式的 icon 图标，会显示在标签页的标题前面。')));

    //  站点副标题
    $form->addInput(new Typecho_Widget_Helper_Form_Element_Text('tagline', null, '生命不息，折腾不止', _t('站点副标题'), _t('站点副标题会显示在标签页标题的后面。')));

    // ICP 备案号
    $form->addInput(new Typecho_Widget_Helper_Form_Element_Text('icp', null, null, _t('ICP备案号'), _t('ICP 备案号会显示在网站的底部，支持 a 标签。')));

    // 面包屑导航
    $form->addInput(new Typecho_Widget_Helper_Form_Element_Radio('breadcrumb', array(
        'on' => '开启',
        'off' => '关闭'
    ), 'off', _t('面包屑导航'), _t('开启后会在导航栏下方显示路径导航。')));

    // 自定义导航栏链接
    $form->addInput(new Typecho_Widget_Helper_Form_Element_Textarea('navLinks', null, null, _t('自定义导航栏链接'), _t('您可以在导航栏添加自定义链接，链接的名称和 URL 都可以自定义，导航栏链接需要使用 JSON 配置 <a href="https://facile.misterma.com/%E4%B8%BB%E9%A2%98%E8%AE%BE%E7%BD%AE.html#%E8%BF%9B%E5%85%A5%E4%B8%BB%E9%A2%98%E8%AE%BE%E7%BD%AE" target="_blank">点击查看配置说明</a>。')));

    // 导航栏图片 logo
    $form->addInput(new Typecho_Widget_Helper_Form_Element_Text('navLogoUrl', null, null, _t('站点 Logo 图片地址'), _t('站点 Logo 图片会显示在顶部导航栏的左侧，支持常见的图片格式，包括 SVG，只要能在 img 标签显示的图片都可以，留空会使用站点名称作为 Logo。')));

    // 站点 logo 图片高度限制
    $form->addInput(new Typecho_Widget_Helper_Form_Element_Text('navLogoHeight', null, '30', _t('站点 Logo 图片高度限制'), _t('如果您发现导航栏 Logo 图片尺寸较小或过大的话，可以调整 Logo 图片的高度，可以直接填入数字，不需要加 px。')));

    //  侧边栏组件顺序
    $form->addInput(new Typecho_Widget_Helper_Form_Element_Text('sidebarComponent', null, '博客信息,主题配色,最新文章,最新回复,文章分类,标签云,文章归档,其它功能,友情链接', _t('侧边栏组件'), _t('您可以设置需要显示在侧边栏的组件，组件会根据这里的组件名称排序。组件名称之间用英文逗号分隔，逗号和名称之间不需要空格，结尾不需要逗号。例如 <b style="color: #C7254E;">博客信息,主题配色,语言选择,最新文章,最新回复,文章分类,标签云,文章归档,其它功能,友情链接</b> 。自定义组件主要用于显示自定义 HTML，开启后需要在下方的 <b style="color: #C7254E;">侧边栏自定义 HTML 内容</b> 表单填写内容后才会显示。')));

    // 文章页的侧边栏组件顺序
    $form->addInput(new Typecho_Widget_Helper_Form_Element_Text('postPageSidebarComponent', null, '博客信息,主题配色,最新文章,目录', _t('文章页的侧边栏组件'), _t('这里可以单独设置文章页的侧边栏组件，组件会根据这里的组件名称排序。组件名称之间用英文逗号分隔，逗号和名称之间不需要空格，结尾不需要逗号。例如 <b style="color: #C7254E;">博客信息,主题配色,语言选择,最新文章,目录</b> 。其中目录组件只能在文章页显示，目录列表项会根据文章内插入的标题生成，如果文章内没有插入标题就不会显示目录，目录组件滚动到页面上方时位置会被固定，建议把目录放到最后。')));

    //  隐藏登录入口
    $form->addInput(new Typecho_Widget_Helper_Form_Element_Radio('loginLink', array(
        'show' => '显示',
        'hide' => '隐藏'
    ), 'show', _t('登录入口'), _t('隐藏登录入口后在前台就不会显示登录入口，只能通过 域名/admin/login.php 进入登录页面')));

    //  侧边栏博客信息博主头像地址
    $form->addInput(new Typecho_Widget_Helper_Form_Element_Text('avatarUrl', null, null, _t('博主头像地址'), _t('博主头像会显示在侧边栏的博客信息区域，如果省略会使用管理员的 Gravatar 头像。')));

    //  侧边栏博客信息区域博主昵称
    $form->addInput(new Typecho_Widget_Helper_Form_Element_Text('nickname', null, null, _t('博主昵称'), _t('博主昵称会显示在侧边栏博客信息区域，如果省略会显示管理员昵称。')));

    //  侧边栏博客信息博主昵称链接
    $form->addInput(new Typecho_Widget_Helper_Form_Element_Text('nicknameUrl', null, null, _t('博主昵称链接调转地址'), _t('在侧边栏的博客信息区域会显示一个包含博主昵称的链接，在这里可以填写链接的跳转地址，如果省略会使用博客首页地址。')));

    //  侧边栏博客信息博主简介
    $form->addInput(new Typecho_Widget_Helper_Form_Element_Text('Introduction', null, null, _t('博主简介'), _t('博主简介会显示在侧边栏博客信息区域的博主昵称下方，如果省略会使用设置中的站点描述信息。')));

    //  侧边栏博客信息的运行天数
    $form->addInput(new Typecho_Widget_Helper_Form_Element_Text('birthday', null, null, _t('站点创建时间'), _t('在这里填写站点创建时间后，在侧边栏的博客信息区域就会显示网站运行天数。如果省略 网站运行天数会从管理员账号创建的时间开始计算天数。站点创建时间的格式为：yyyy-mm-dd，例如：2019-11-11。')));

    //  侧边栏标签数量
    $form->addInput(new Typecho_Widget_Helper_Form_Element_Text('tagCount', null, '0', _t('侧边栏标签云标签数量'), _t('对于标签较多的博客，可以设置侧边栏显示的标签数量，0 为不限制。')));

    // 侧边栏自定义HTML标题
    $form->addInput(new Typecho_Widget_Helper_Form_Element_Text('customizeTitle', null, '公告', _t('侧边栏自定义 HTML 组件标题'), _t('如果您启用了侧边栏的自定义 HTML 组件，可以在这里给组件设置一个标题，这个标题会显示在组件上方。')));

    // 侧边栏自定义HTML
    $form->addInput(new Typecho_Widget_Helper_Form_Element_Textarea('customizeHTML', null, null, _t('侧边栏自定义 HTML 内容'), _t('如果您启用了侧边栏的自定义 HTML 组件，可以在这里输入 HTML，支持纯文本和 HTML，包括 img、audio、video、canvas。您可以用来设置网站公告内容或广告。')));

    // 文章列表显示设置
    $form->addInput(new Typecho_Widget_Helper_Form_Element_Radio('postListStyle', array(
        'fullText' => '文章列表直接显示全文',
        'summary' => '文章列表显示摘要和文章头图'
    ), 'summary', _t('文章列表显示'), _t('文章列表包括首页、搜索页、归档 左侧的文章列表。在显示全文的情况下，文章列表不会显示文章头图，显示全文也支持使用 <b style="color: #C7254E;">&lt!--more--&gt</b> 来手动分隔摘要。如果你想自定义单篇文章的列表显示，你也可以在文章编辑页单独设置列表显示。')));

    //  文章摘要字数
    $form->addInput(new Typecho_Widget_Helper_Form_Element_Text('summary', null, '130', _t('文章摘要字数'), _t('首页、分类页、标签页、搜索页 的文章摘要字数，默认为：130个字。')));

    // 移动设备章节目录
    $form->addInput(new Typecho_Widget_Helper_Form_Element_Radio('directoryMobile', array(
        'enable' => '启用',
        'disabled' => '禁用'
    ), 'enable', _t('移动设备章节目录'), _t('开启后在没有侧边栏的小屏移动设备右下方会显示一个目录按钮，点击可以打开章节目录列表。')));

    // 启用代码高亮功能
    $form->addInput(new Typecho_Widget_Helper_Form_Element_Radio('codeHighlight', array(
        'enable-highlight' => '启用',
        'disabled-highlight' => '禁用'
    ), 'enable-highlight', _t('代码高亮'), _t('您可以设置是否启用文章内的代码块高亮，如果您需要使用其他代码高亮插件的话，可以禁用主题自带的代码高亮功能。')));

    // 显示代码行号
    $form->addInput(new Typecho_Widget_Helper_Form_Element_Radio('codeLineNum', array(
        'show' => '显示',
        'hide' => '不显示'
    ), 'show', _t('代码块显示行号'), _t('开启后文章的代码块会显示行号')));

    // 代码块配色
    $form->addInput(new Typecho_Widget_Helper_Form_Element_Radio('codeThemeColor', array(
        'stackoverflow-light' => 'Stack Overflow（浅色）',
        'vs2015' => 'VS2015（深色）',
        'sunburst' => 'Sunburst（高对比度）'
    ), 'vs2015', _t('代码块颜色主题')));

    // 图片懒加载
    $form->addInput(new Typecho_Widget_Helper_Form_Element_Radio('imagelazyloading', array(
        'on' => '启用',
        'off' => '禁用'
    ), 'off', _t('图片懒加载'), _t('开启后文章内的图片不会自动加载，只有图片进入页面可视区才会加载')));

    //  文章头图设置
    $headerImage = new Typecho_Widget_Helper_Form_Element_Checkbox('headerImage', array(
        'home' => _t('在文章列表显示文章头图'),
        'post' => _t('在文章页显示文章头图')
    ), array('home', 'post'), _t('文章头图显示设置'), _t('这里可以统一设置文章头图的显示和隐藏，您也可以在文章编辑页给文章单独设置显示和隐藏。'));
    $form->addInput($headerImage->multiMode());

    // 文章列表的文章头图样式
    $form->addInput(new Typecho_Widget_Helper_Form_Element_Radio('postListHeaderImageStyle', array(
        'max' => '大图（文章头图在上方，文章标题和摘要在下方）',
        'mini' => '小图（文章标题和摘要在左侧，图片在右侧）'
    ), 'max', _t('文章列表的文章头图样式'), _t('这里可以统一设置文章头图的样式，您也可以在文章编辑页给文章单独设置头图样式。大图的图片长宽比为 18 比 9，小图的长宽比为 4 比 3，如果图片长宽比不符合要求，主题会自动裁剪图片来适配长宽比。')));

    // 文章头图风格
    $form->addInput(new Typecho_Widget_Helper_Form_Element_Radio('headerImageStyle', array(
        'right-angle' => '直角',
        'rounded-corners' => '圆角'
    ), 'right-angle', _t('文章头图风格')));

    //  默认文章头图
    $form->addInput(new Typecho_Widget_Helper_Form_Element_Textarea('headerImageUrl', null, null, _t('默认文章头图'), _t('这里可以填写默认的文章头图 URL，一行一个，系统会在默认文章头图地址中随机选择一个来加载文章头图。要使用默认文章头图，文章编辑页的文章头图来源需要设置为 使用系统设置。')));

    // 文章底部的交互功能配置
    $form->addInput(new Typecho_Widget_Helper_Form_Element_Text('engagementSection', null, '点赞,分享', _t('文章底部的交互功能'), _t('文章底部要使用的交互功能，支持 <b style="color: #C7254E;">点赞,打赏,分享</b>，功能名称之间用英文逗号分隔，逗号之间不需要空格，结尾不需要逗号，功能按钮的顺序会根据这里设置的名称顺序排序。')));

    // 打赏二维码地址
    $form->addInput(new Typecho_Widget_Helper_Form_Element_Text('rewardQr', null, '', _t('打赏二维码图片地址'), _t('文章下方的打赏按钮点击后可以显示一个二维码图片，你可以在这里设置图片地址，图片的最大宽度就是文章区域的宽度，高度不限制，图片会居中显示。')));

    //  评论日期时间格式
    $form->addInput(new Typecho_Widget_Helper_Form_Element_Radio('commentDateFormat', array(
        'format1' => '2020年04月23日 13:09',
        'format2' => '2020-04-23 13:09',
        'format3' => 'April 23rd, 2020 at 01:09 pm',
        'format4' => '时间间隔（3天前）'
    ), 'format1', _t('评论日期时间格式'), _t('时间间隔的单位会根据间隔长短变化，不到一分钟的单位为 秒，一分钟以上、一小时以下的单位为 分钟，一小时以上、一天以下的单位为 小时，一天以上的单位为 天，')));

    //  评论框位置
    $form->addInput(new Typecho_Widget_Helper_Form_Element_Radio('commentInput', array(
        'top' => '评论框在评论列表上方',
        'bottom' => '评论框在评论列表下方'
    ), 'bottom', _t('评论框位置'), _t('评论框就是发表评论的区域，评论列表就是已发表的评论区域')));

    //  使用QQ头像
    $form->addInput(new Typecho_Widget_Helper_Form_Element_Radio('QQAvatar', array(
        'show' => '显示',
        'hide' => '不显示'
    ), 'hide', _t('显示评论者的QQ头像'), _t('开启后如果检测到评论者使用QQ邮箱就会显示QQ头像，只支持 QQ号@qq.com 的QQ邮箱。')));

    // 自定义 Gravatar 地址
    $form->addInput(new Typecho_Widget_Helper_Form_Element_Text('gravatarUrl', null, '', _t('自定义 Gravatar 源'), _t('Gravatar 头像服务在有些地区可能无法正常使用，如果你需要更换 Gravatar 源的话，可以在这里输入 URL，留空会使用官方源。')));

    //  启用 Emoji 面板
    $form->addInput(new Typecho_Widget_Helper_Form_Element_Radio('emojiPanel', array(
        'show' => '启用',
        'hide' => '禁用'
    ), 'show', _t('Emoji 表情面板'), _t('开启后在评论内容输入框下方会出现一个 Emoji  表情按钮，点击可以打开表情面板。')));

    //  首页友链
    $form->addInput(new Typecho_Widget_Helper_Form_Element_Textarea('homeLinks', null, null, _t('首页友情链接'), _t('首页友情链接只会显示在首页的侧边栏，需要 JSON 格式数据。 <a href="https://facile.misterma.com/%E4%B8%BB%E9%A2%98%E8%AE%BE%E7%BD%AE.html" target="_blank">点击查看友情链接设置说明</a>，你也可以使用链接编辑器编辑，无需手动输入 JSON。。 <button data-title="首页友情链接" data-name="homeLinks" type="button" class="btn show-link-editor">打开链接编辑器</button>')));

    //  全站友链
    $form->addInput(new Typecho_Widget_Helper_Form_Element_Textarea('links', null, null, _t('全站友情链接'), _t('全站友情链接会在每个页面的侧边栏显示，需要 JSON 格式数据。<a href="https://facile.misterma.com/%E4%B8%BB%E9%A2%98%E8%AE%BE%E7%BD%AE.html" target="_blank">点击查看友情链接设置说明</a>，你也可以使用链接编辑器编辑，无需手动输入 JSON。 <button data-title="全站友情链接" data-name="links" type="button" class="btn show-link-editor">打开链接编辑器</button>')));

    //  独立页友链
    $form->addInput(new Typecho_Widget_Helper_Form_Element_Textarea('pageLinks', null, null, _t('独立页友情链接'), _t('独立页友情链接只会在友情链接的页面显示，需要 JSON 格式 数据。如果要使用独立页友情链接需要创建一个独立页面，把 自定义模板设置为 <b style="color: #C7254E;">友情链接</b>。<a href="https://facile.misterma.com/%E4%B8%BB%E9%A2%98%E8%AE%BE%E7%BD%AE.html" target="_blank">点击查看友情链接设置说明</a>，你也可以使用链接编辑器编辑，无需手动输入 JSON。 <button data-title="独立页友情链接" data-name="pageLinks" type="button" class="btn show-link-editor">打开链接编辑器</button>')));

    // PJAX
    $form->addInput(new Typecho_Widget_Helper_Form_Element_Radio('pjax', array(
        'on' => '启用',
        'off' => '禁用'
    ), 'off', _t('启用PJAX'), _t('PJAX 在页面跳转时只会更新内容部分，不会刷新整个页面，可以实现类似于单页应用的使用体验。注意，目前 Typecho 主题的 PJAX 还无法做到和 Typecho 程序完美兼容，如果要启用 PJAX，需要在 Typecho 评论设置中关闭 <b style="color: #C7254E;">开启反垃圾保护</b> 和 <b style="color: #C7254E;">检查评论来源页 URL 是否与文章链接一致</b>，否则评论可能无法成功发送！')));

    // PJAX进度条
    $form->addInput(new Typecho_Widget_Helper_Form_Element_Radio('pjaxProgressBar', array(
        'on' => '启用',
        'off' => '禁用'
    ), 'on', _t('PJAX进度条'), _t('PJAX 进度条会显示在页面顶部，在 PJAX 页面内容更新时，进度条会显示更新进度，更新完成后进度条会隐藏。禁用 PJAX 进度条不会影响 PJAX 功能。')));

    // PJAX更新完成后执行的代码
    $form->addInput(new Typecho_Widget_Helper_Form_Element_Textarea('pjaxEnd', null, null, _t('PJAX 更新完成后要执行的 JS 代码'), _t('这里的 JS 代码会在页面内容更新完成后执行，你可以直接填写 JS 代码，不需要加 script 标签。<b style="color: #C7254E;">注意，使用 Webpack 打包的带 bundle 后缀的发行版不支持这个选项，只有 clone 项目和 Source code 支持！</b>')));

    //  自定义CSS
    $form->addInput(new Typecho_Widget_Helper_Form_Element_Textarea('cssCode', null, null, _t('自定义 CSS'), _t('通过自定义 CSS 您可以很方便的设置页面样式，自定义 CSS 不会影响网站源代码。')));

    //  自定义 head 输出的 HTML
    $form->addInput(new Typecho_Widget_Helper_Form_Element_Textarea('headHTML', null, null, _t('自定义 head 区域输出的 HTML'), _t('head 区域的 HTML 会在 head 内输出，可以用来定义一些网站统计的 JS 之类的。')));

    //  自定义 body 底部的 HTML
    $form->addInput(new Typecho_Widget_Helper_Form_Element_Textarea('bodyHTML', null, null, _t('自定义 body 底部输出的 HTML'), _t('body 底部的 HTML 会在 footer 之后 body 尾部之前输出。')));
}