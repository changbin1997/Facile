<?php

//  文章的自定义字段
function themeFields($layout) {
    // 文章头图显示设置
    $layout->addItem(new Typecho_Widget_Helper_Form_Element_Select('headerImgDisplay', array(
        'default' => '使用系统设置',
        'post-page-list' => '在文章列表和文章页显示文章头图',
        'post-list' => '只在文章列表显示文章头图',
        'post-page' => '只在文章页显示文章头图',
        'hide' => '不显示文章头图'
    ), 'default', _t('文章头图显示设置'), _t('您可以单独给文章设置文章头图显示。')));

    //  文章头图来源
    $layout->addItem(new Typecho_Widget_Helper_Form_Element_Select('imageSource', array(
        'article' => '使用文章中的第一张图片作为文章头图',
        'url' => '在文章头图输入框手动输入图片URL'
    ), 'article', _t('文章头图来源'), _t('如果选择了使用文章中的第一张图片作为文章头图，在文章不包含图片的情况下将不会显示文章头图。')));

    //  文章头图
    $layout->addItem(new Typecho_Widget_Helper_Form_Element_Text('thumb', null, null, _t('文章头图'), _t('如果您在文章头图来源中设置了手动输入图片 URL 的话，请在这里输入图片 URL。')));

    //  自定义文章摘要内容
    $layout->addItem(new Typecho_Widget_Helper_Form_Element_Textarea('summaryContent', null, null, _t('自定义摘要内容'), _t('您可以在此处为文章定义摘要内容，此处定义的摘要内容不受字数限制。')));

    //  自定义关键词
    $layout->addItem(new Typecho_Widget_Helper_Form_Element_Text('keywords', null, null, _t('自定义关键词'), _t('您可以输入这篇文章的关键词，多个关键词之间用英文逗号分隔，如果为空 会使用这篇文章的标签作为关键词。')));

    // 文章有效期
    $layout->addItem(new Typecho_Widget_Helper_Form_Element_Text('expired', null, '0', _t('文章有效期'), _t('有的文章可能只是在某个时间段内有用，发布后如果长时间不更新的话，可能会给读者带去错误的信息。文章有效期可以设置一个天数，过了指定天数后，在文章开头会显示一条警示信息。0 或留空不显示。')));
}

//  主题设置
function themeConfig($form) {
    echo <<<EOT
    <p>您现在使用的是 Facile 的开发板，开发板暂无版本号。<a href="https://github.com/changbin1997/Facile/releases" target="_blank">点击查看发行版</a></p>
    <p>主题使用帮助可以简单参考 <a href="https://mwordstar.misterma.com/" target="_blank">MWordStar</a> 的帮助文档，遇到问题也可以到 <a href="https://www.misterma.com/msg.html" target="_blank">留言板</a> 或 <a href="https://www.misterma.com/archives/899/" target="_blank">Facile 介绍页</a> 留言。因为我有两个主题，为了更高效的解决问题，建议到 <a href="https://www.misterma.com/archives/899/" target="_blank">Facile 介绍页</a> 留言。</p>
    <button id="export-btn" type="button" class="btn">导出主题配置文件</button>
    <button id="import-btn" type="button" class="btn">导入主题配置文件</button>
    <a href="javascript:;" id="download-file" style="display: none;">下载</a>
    <input type="file" id="file-select" style="display: none;">
    <p><b>导出主题配置文件</b> 可以把主题外观设置导出为 JSON 文件，主要用来备份主题设置，<b>导入主题配置文件</b> 可以导入 <b>Facile</b> 主题的 JSON 配置文件。Typecho 切换主题的时候会清空主题设置，为了避免重复设置，在切换主题之前可以先导出主题设置配置。</p>
EOT;
    echo '<script type="text/javascript">';
    require_once 'assets/js/options-panel.js';
    echo '</script>';

    // 主题配色
    $form->addInput(new Typecho_Widget_Helper_Form_Element_Radio('themeColor', array(
        'light' => '浅色主题',
        'dark' => '深色主题'
    ), 'light', _t('默认主题配色'), _t('主题配色会优先使用访问者设置的配色，如果访问者没有更改过配色就会使用默认设置。主题配色设置组件可以在侧边栏组件设置中添加或删除。')));

    //  站点Logo
    $form->addInput(new Typecho_Widget_Helper_Form_Element_Text('logoUrl', null, null, _t('站点 Logo 地址'), _t('Logo 是一个 ico 格式的 icon 图标，会显示在标签页的标题前面。')));

    //  站点副标题
    $form->addInput(new Typecho_Widget_Helper_Form_Element_Text('tagline', null, '生命不息，折腾不止', _t('站点副标题'), _t('站点副标题会显示在标签页标题的后面。')));

    // ICP 备案号
    $form->addInput(new Typecho_Widget_Helper_Form_Element_Text('icp', null, null, _t('ICP备案号'), _t('ICP 备案号会显示在网站的底部，支持 a 标签。')));

    // 面包屑导航
    $form->addInput(new Typecho_Widget_Helper_Form_Element_Radio('breadcrumb', array(
        'on' => '开启',
        'off' => '关闭'
    ), 'off', _t('面包屑导航'), _t('开启后会在导航栏下方显示路劲导航。')));

    //  侧边栏组件顺序
    $form->addInput(new Typecho_Widget_Helper_Form_Element_Text('sidebarComponent', null, '主题配色,最新文章,最新回复,文章分类,标签云,文章归档,其它功能,友情链接', _t('侧边栏组件'), _t('您可以设置需要显示在侧边栏的组件，组件会根据这里的组件名称排序。组件名称之间用英文逗号分隔，逗号和名称之间不需要空格，结尾不需要逗号。例如 主题配色,最新文章,最新回复,文章分类,标签云,文章归档,其它功能,友情链接 。')));

    //  隐藏登录入口
    $form->addInput(new Typecho_Widget_Helper_Form_Element_Radio('loginLink', array(
        'show' => '显示',
        'hide' => '隐藏'
    ), 'show', _t('登录入口'), _t('隐藏登录入口后在前台就不会显示登录入口，只能通过 域名/admin/login.php 进入登录页面')));

    //  文章摘要字数
    $form->addInput(new Typecho_Widget_Helper_Form_Element_Text('summary', null, '120', _t('文章摘要字数'), _t('首页、分类页、标签页、搜索页 的文章摘要字数，默认为：120个字。')));

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

    //  文章头图设置
    $headerImage = new Typecho_Widget_Helper_Form_Element_Checkbox('headerImage', array(
        'home' => _t('在首页显示文章头图'),
        'post' => _t('在文章页显示文章头图')
    ), array('home', 'post'), _t('文章头图设置'));
    $form->addInput($headerImage->multiMode());

    // 文章头图风格
    $form->addInput(new Typecho_Widget_Helper_Form_Element_Radio('headerImageStyle', array(
        'right-angle' => '直角',
        'rounded-corners' => '圆角'
    ), 'right-angle', _t('文章头图风格')));

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

    //  启用 Emoji 面板
    $form->addInput(new Typecho_Widget_Helper_Form_Element_Radio('emojiPanel', array(
        'show' => '启用',
        'hide' => '禁用'
    ), 'show', _t('Emoji 表情面板'), _t('开启后在评论内容输入框下方会出现一个 Emoji  表情按钮，点击可以打开表情面板。')));

    //  首页友链
    $form->addInput(new Typecho_Widget_Helper_Form_Element_Textarea('homeLinks', null, null, _t('首页友情链接'), _t('首页友情链接只会显示在首页的侧边栏，需要 JSON 格式数据。如需查看详细说明可以访问：https://mwordstar.misterma.com/docs/doc10 。')));

    //  全站友链
    $form->addInput(new Typecho_Widget_Helper_Form_Element_Textarea('links', null, null, _t('全站友情链接'), _t('全站友情链接会在每个页面的侧边栏显示，需要 JSON 格式数据。如需查看详细说明可以访问：https://mwordstar.misterma.com/docs/doc10 。')));

    //  独立页友链
    $form->addInput(new Typecho_Widget_Helper_Form_Element_Textarea('pageLinks', null, null, _t('独立页友情链接'), _t('独立页友情链接只会在友情链接的页面显示，需要 JSON 格式 数据。如果要使用独立页友情链接需要创建一个独立页面，把 自定义模板设置为 友情链接。如需查看详细说明可以访问：https://mwordstar.misterma.com/docs/doc10 。')));

    //  自定义CSS
    $form->addInput(new Typecho_Widget_Helper_Form_Element_Textarea('cssCode', null, null, _t('自定义 CSS'), _t('通过自定义 CSS 您可以很方便的设置页面样式，自定义 CSS 不会影响网站源代码。')));

    //  自定义 head 输出的 HTML
    $form->addInput(new Typecho_Widget_Helper_Form_Element_Textarea('headHTML', null, null, _t('自定义 head 区域输出的 HTML'), _t('head 区域的 HTML 会在 head 内输出，可以用来定义一些网站统计的 JS 之类的。')));

    //  自定义 body 底部的 HTML
    $form->addInput(new Typecho_Widget_Helper_Form_Element_Textarea('bodyHTML', null, null, _t('自定义 body 底部输出的 HTML'), _t('body 底部的 HTML 会在 footer 之后 body 尾部之前输出。')));
}

//  获取点赞数量
function agreeNum($cid) {
    $db = Typecho_Db::get();
    $prefix = $db->getPrefix();

    if (!array_key_exists('agree', $db->fetchRow($db->select()->from('table.contents')))) {
        $db->query('ALTER TABLE `' . $prefix . 'contents` ADD `agree` INT(10) NOT null DEFAULT 0;');
    }

    $agree = $db->fetchRow($db->select('table.contents.agree')->from('table.contents')->where('cid = ?', $cid));
    $AgreeRecording = Typecho_Cookie::get('typechoAgreeRecording');
    if (empty($AgreeRecording)) {
        Typecho_Cookie::set('typechoAgreeRecording', json_encode(array(0)));
    }

    return array(
        //  点赞数量
        'agree' => $agree['agree'],
        //  文章是否点赞过
        'recording' => in_array($cid, json_decode(Typecho_Cookie::get('typechoAgreeRecording')))?true:false
    );
}

//  点赞
function agree($cid) {
    $db = Typecho_Db::get();
    $agree = $db->fetchRow($db->select('table.contents.agree')->from('table.contents')->where('cid = ?', $cid));
    $agreeRecording = Typecho_Cookie::get('typechoAgreeRecording');
    if (empty($agreeRecording)) {
        Typecho_Cookie::set('typechoAgreeRecording', json_encode(array($cid)));
    }else {
        $agreeRecording = json_decode($agreeRecording);
        //  判断文章是否点赞过
        if (in_array($cid, $agreeRecording)) {
            //  如果当前文章的 cid 在 cookie 中就返回文章的赞数，不再往下执行
            return $agree['agree'];
        }
        array_push($agreeRecording, $cid);
        Typecho_Cookie::set('typechoAgreeRecording', json_encode($agreeRecording));
    }

    $db->query($db->update('table.contents')->rows(array('agree' => (int)$agree['agree'] + 1))->where('cid = ?', $cid));
    $agree = $db->fetchRow($db->select('table.contents.agree')->from('table.contents')->where('cid = ?', $cid));
    return $agree['agree'];
}

//  获取分类数量
function categoryCount() {
    $db = Typecho_Db::get();
    $count = $db->fetchRow($db->select('COUNT(*)')->from('table.metas')->where('type = ?', 'category'));
    return $count['COUNT(*)'];
}

//  获取标签数量
function tagCount() {
    $db = Typecho_Db::get();
    $count = $db->fetchRow($db->select('COUNT(*)')->from('table.metas')->where('type = ?', 'tag'));
    return $count['COUNT(*)'];
}

//  获取总阅读量
function viewsCount() {
    $db = Typecho_Db::get();
    $count = $db->fetchRow($db->select('SUM(views) AS viewsCount')->from('table.contents'));
    return $count['viewsCount'];
}

//  获取总点赞数
function agreeCount() {
    $db = Typecho_Db::get();
    $count = $db->fetchRow($db->select('SUM(agree) AS agreeCount')->from('table.contents'));
    return $count['agreeCount'];
}

//  获取 ECharts 格式要求的文章更新日历
function postCalendar($start, $end) {
    $db = Typecho_Db::get();
    $dateList = $db->fetchAll($db->select('created')->from('table.contents')->where('created > ?', $start)->where('created < ?', $end));
    if (count($dateList) < 1) {
        return array();
    }
    $dateList2 = array();
    foreach ($dateList as $val) {
        array_push($dateList2, date('Y-m-d', $val['created']));
    }
    $dateList2 = array_count_values($dateList2);
    $key = array_keys($dateList2);
    $dateList = array();

    for ($i = 0;$i < count($dateList2);$i ++) {
        array_push($dateList, array(
            $key[$i],
            $dateList2[$key[$i]]
        ));
    }

    return $dateList;
}

//  获取 ECharts 格式要求的评论更新日历
function commentCalendar($start, $end) {
    $db = Typecho_Db::get();
    $dateList = $db->fetchAll($db->select('created')->from('table.comments')->where('created > ?', $start)->where('created < ?', $end));
    if (count($dateList) < 1) {
        return array();
    }
    $dateList2 = array();
    foreach ($dateList as $val) {
        array_push($dateList2, date('Y-m-d', $val['created']));
    }
    $dateList2 = array_count_values($dateList2);
    $key = array_keys($dateList2);
    $dateList = array();

    for ($i = 0;$i < count($dateList2);$i ++) {
        array_push($dateList, array(
            $key[$i],
            $dateList2[$key[$i]]
        ));
    }

    return $dateList;
}

//  获取个分类的文章数量
function categoryPostCount() {
    $db = Typecho_Db::get();
    $count = $db->fetchAll($db->select('name', 'count AS value')->from('table.metas')->where('type = ?', 'category'));
    if (count($count) < 1) {
        return array();
    }
    return $count;
}

//  获取阅读量排名前 5 的 5 篇文章的信息
function top5post() {
    $db = Typecho_Db::get();
    $top5Post = $db->fetchAll($db->select()->from('table.contents')->where('type = ?', 'post')->where('status = ?', 'publish')->order('views', Typecho_Db::SORT_DESC)->offset(0)->limit(5));
    $postList =array();
    foreach ($top5Post as $post) {
        $post = Typecho_Widget::widget('Widget_Abstract_Contents')->filter($post);
        array_push($postList, array(
            'title' => $post['title'],
            'link' => $post['permalink'],
            'views' => $post['views']
        ));
    }
    return $postList;
}

//  获取评论数排名前 5 的 5 篇文章的信息
function top5CommentPost() {
    $db = Typecho_Db::get();
    $top5Post = $db->fetchAll($db->select()->from('table.contents')->where('type = ?', 'post')->where('status = ?', 'publish')->order('commentsNum', Typecho_Db::SORT_DESC)->offset(0)->limit(5));
    $postList = array();
    foreach ($top5Post as $post) {
        $post = Typecho_Widget::widget('Widget_Abstract_Contents')->filter($post);
        array_push($postList, array(
            'title' => $post['title'],
            'link' => $post['permalink'],
            'commentsNum' => $post['commentsNum']
        ));
    }
    return $postList;
}

//  获取父评论的姓名
function reply($parent) {
    if ($parent == 0) {
        return '';
    }

    $db = Typecho_Db::get();
    $commentInfo = $db->fetchRow($db->select('author,status,mail')->from('table.comments')->where('coid = ?', $parent));
    $link = '<span class="mx-2">回复</span><b><a class="parent mr-1" href="#comment-' . $parent . '">' . $commentInfo['author'] .  '</a></b>';
    return $link;
}

//  统计文章阅读量
function getPostViews($archive) {
    $cid = $archive->cid;
    $db = Typecho_Db::get();
    $prefix = $db->getPrefix();
    if (!array_key_exists('views', $db->fetchRow($db->select()->from('table.contents')))) {
        $db->query('ALTER TABLE `' . $prefix . 'contents` ADD `views` INT(10) DEFAULT 0;');
        return 0;
    }
    $row = $db->fetchRow($db->select('views')->from('table.contents')->where('cid = ?', $cid));
    if ($archive->is('single')) {
        $views = Typecho_Cookie::get('extend_contents_views');
        if (empty($views)) {
            $views = array();
        } else {
            $views = explode(',', $views);
        }
        if (!in_array($cid, $views)) {
            //  如果cookie不存在才会加1
            $db->query($db->update('table.contents')->rows(array('views' => (int)$row['views'] + 1))->where('cid = ?', $cid));
            array_push($views, $cid);
            $views = implode(',', $views);
            Typecho_Cookie::set('extend_contents_views', $views);  //  记录查看cookie
        }
    }
    return $row['views'];
}

//  检测是否是QQ邮箱
function isQQEmail($email) {
    $re = '/^\d{6,11}\@qq\.com$/';
    preg_match($re, $email, $result);
    if (count($result)) {
        return true;
    }
    return false;
}

//  获取QQ头像
function QQAvatar($email, $name, $size) {
    $qq = str_replace('@qq.com', '', $email);  //  获取QQ号
    $imgUrl = 'https://q2.qlogo.cn/headimg_dl?dst_uin=' . $qq . '&spec=' . $size;
    echo '<img src="' . $imgUrl . '" alt="' . $name . '" class="avatar">';
}

//  日期格式化
function dateFormat($date, $options = 'format1') {
    if ($options == 'format1') {
        return date('Y年m月d日 H:i', $date);
    }
    if ($options == 'format2') {
        return date('Y-m-d H:i', $date);
    }
    if ($options == 'format3') {
        return date('F jS, Y \a\t h:i a', $date);
    }
    if ($options == 'format4') {
        $time = time() - $date;
        if ($time < 1) {
            return '1秒前';
        }else if ($time < 60) {
            return $time . '秒前';
        }else if ($time > 60 && $time < 3600) {
            return round($time / 60, 0) . '分钟前';
        }else if ($time > 3600 && $time < 86400) {
            return round($time / 3600, 0) . '小时前';
        }else {
            return round($time / 86400, 0) . '天前';
        }
    }
}

// 获取文章头图显示设置
function headerImageDisplay($t, $options) {
    // 在文章列表和文章页显示文章头图
    if ($t->fields->headerImgDisplay == 'post-page-list') {
        return postImg($t);
    }
    // 在文章列表显示文章头图
    if ($t->fields->headerImgDisplay == 'post-list' && $t->is('index') or $t->is('archive')) {
        return postImg($t);
    }
    // 在文章页显示文章头图
    if ($t->fields->headerImgDisplay == 'post-page' && $t->is('post')) {
        return postImg($t);
    }
    // 使用系统文章头图设置
    if ($t->fields->headerImgDisplay == 'default' or $t->fields->headerImgDisplay == null) {
        if (is_array($options) && in_array('home', $options) && $t->is('index')) {
            return postImg($t);
        }
        if (is_array($options) && in_array('post', $options) && $t->is('post') or $t->is('page')) {
            return postImg($t);
        }
    }
    // 不显示文章头图
    if ($t->fields->headerImgDisplay == 'hide') return false;
    return false;
}

//  根据设置获取文章头图
function postImg($a) {
    // 手动输入文章头图
    if ($a->fields->imageSource == 'url' && $a->fields->thumb != '') {
        return $a->fields->thumb;
    }
    // 默认使用第一张图片作为文章头图
    $img = getPostImg($a);
    return $img == 'none'?false:$img;
}

//  获取文章的第一张图片
function getPostImg($archive) {

    $img = array();
    preg_match_all("/<img.*?src=\"(.*?)\".*?\/?>/i", $archive->content, $img);
    if (count($img) > 0 && count($img[0]) > 0) {
        $img_url = $img[1][0];
        return $img_url;
    } else {
        return 'none';
    }
}

// 获取父分类的名称
function getParentCategory($categoryId) {
    $db = Typecho_Db::get();
    $category = $db->fetchRow($db->select()->from('table.metas')->where('mid = ?', $categoryId));
    return $category['name'];
}

// 计算两个时间之间相差的天数
function getDays($time1, $time2) {
    return floor(($time2 - $time1) / 86400);
}