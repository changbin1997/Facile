<?php

// 设置语言
function languageInit($language) {
    $languageList = array('zh', 'en');

    // 如果有语言设置 Cookie 就优先使用 Cookie 存储的语言
    if (isset($_COOKIE['language']) && $_COOKIE['language'] != '') $language = $_COOKIE['language'];

    // 自动选择
    if ($language == 'auto') {
        if (!isset($_SERVER['HTTP_ACCEPT_LANGUAGE']) or $_SERVER['HTTP_ACCEPT_LANGUAGE'] == null) {
           $language = 'en';
        }else {
            $userLanguage = locale_accept_from_http($_SERVER['HTTP_ACCEPT_LANGUAGE']);
            $language = substr($userLanguage, 0, 2);
            // 如果用户浏览器的语言是不支持的语言就使用英语
            if (!in_array($language, $languageList)) $language = 'en';
        }
    }

    // 选择中文
    if ($language == 'zh-CN' or $language == 'zh' or $language == null) {
        require_once __DIR__ . '/../languages/zh.php';
        $GLOBALS['t'] = ZH;
    }
    // 选择英文
    if ($language == 'en') {
        require_once __DIR__ . '/../languages/en.php';
        $GLOBALS['t'] = EN;
    }

    $GLOBALS['language'] = $language == null ? 'zh-CN' : $language;
}

// 把一些翻译内容传给 JS
function localizeScript() {
    // 需要传给 JS 的翻译内容
    $t = array(
        'pressEnterToAddTheEmojiToTheCommentInputField' => $GLOBALS['t']['emoji']['pressEnterToAddTheEmojiToTheCommentInputField'],
        'nextPage' => $GLOBALS['t']['pagination']['nextPage'],
        'previousPage' => $GLOBALS['t']['pagination']['previousPage'],
        'zoomIn' => $GLOBALS['t']['imageLightbox']['zoomIn'],
        'zoomOut' => $GLOBALS['t']['imageLightbox']['zoomOut'],
        'rotateLeft' => $GLOBALS['t']['imageLightbox']['rotateLeft'],
        'rotateRight' => $GLOBALS['t']['imageLightbox']['rotateRight'],
        'closeImage' => $GLOBALS['t']['imageLightbox']['closeImage'],
        'copyCode' => $GLOBALS['t']['code']['copyCode'],
        'copySuccess' => $GLOBALS['t']['code']['copySuccess'],
        'copyError' => $GLOBALS['t']['code']['copyError'],
        'cancelReply' => $GLOBALS['t']['comment']['cancelReply'],
        'enterThePasswordToViewIt' => $GLOBALS['t']['post']['enterThePasswordToViewIt'],
        'enterYourPassword' => $GLOBALS['t']['post']['enterYourPassword'],
        'submit' => $GLOBALS['t']['post']['submit'],
        'replyTo' => $GLOBALS['t']['comment']['replyTo'],
        'like' => $GLOBALS['t']['post']['like'],
        'categoryDistribution' => $GLOBALS['t']['dataPage']['categoryDistribution']
    );
    $t = json_encode($t, JSON_UNESCAPED_UNICODE);
    echo '<script type="text/javascript"> window.t = ' . $t . ' </script>';
}

// 根据语言格式化文章日期
function postDateFormat($date) {
    if ($GLOBALS['language'] == 'zh' or $GLOBALS['language'] == 'zh-CN') {
        $date = date('Y年m月d日', $date);
    }else {
        $date = date('j M Y', $date);
    }
    return $date;
}

// 获取英文的日序数后缀
function getDayWithSuffix($timestamp) {
    // 提取日期中的天
    $day = date('j', $timestamp);
    // 根据天数返回对应的后缀
    if (!in_array(($day % 100), [11, 12, 13])) {
        switch ($day % 10) {
            case 1: return $day . 'st';
            case 2: return $day . 'nd';
            case 3: return $day . 'rd';
        }
    }
    return $day . 'th';
}

// 获取点赞数量
function agreeNum($cid) {
    $db = Typecho_Db::get();
    $prefix = $db->getPrefix();

    $agree = $db->fetchRow($db->select('table.contents.agree')->from('table.contents')->where('cid = ?', $cid));
    $AgreeRecording = Typecho_Cookie::get('typechoAgreeRecording');
    if (empty($AgreeRecording)) {
        Typecho_Cookie::set('typechoAgreeRecording', json_encode(array(0)));
    }

    return array(
        // 点赞数量
        'agree' => $agree['agree'],
        // 文章是否点赞过
        'recording' => in_array($cid, json_decode(Typecho_Cookie::get('typechoAgreeRecording')))?true:false
    );
}

// 点赞
function agree($cid) {
    $db = Typecho_Db::get();
    $agree = $db->fetchRow($db->select('table.contents.agree')->from('table.contents')->where('cid = ?', $cid));
    $agreeRecording = Typecho_Cookie::get('typechoAgreeRecording');
    if (empty($agreeRecording)) {
        Typecho_Cookie::set('typechoAgreeRecording', json_encode(array($cid)));
    }else {
        $agreeRecording = json_decode($agreeRecording);
        // 判断文章是否点赞过
        if (in_array($cid, $agreeRecording)) {
            // 如果当前文章的 cid 在 cookie 中就返回文章的赞数，不再往下执行
            return $agree['agree'];
        }
        array_push($agreeRecording, $cid);
        Typecho_Cookie::set('typechoAgreeRecording', json_encode($agreeRecording));
    }

    $db->query($db->update('table.contents')->rows(array('agree' => (int)$agree['agree'] + 1))->where('cid = ?', $cid));
    $agree = $db->fetchRow($db->select('table.contents.agree')->from('table.contents')->where('cid = ?', $cid));
    return $agree['agree'];
}

// 获取分类数量
function categoryCount() {
    $db = Typecho_Db::get();
    $count = $db->fetchRow($db->select('COUNT(*)')->from('table.metas')->where('type = ?', 'category'));
    return $count['COUNT(*)'];
}

// 获取标签数量
function tagCount() {
    $db = Typecho_Db::get();
    $count = $db->fetchRow($db->select('COUNT(*)')->from('table.metas')->where('type = ?', 'tag'));
    return $count['COUNT(*)'];
}

// 获取总阅读量
function viewsCount() {
    $db = Typecho_Db::get();
    $count = $db->fetchRow($db->select('SUM(views) AS viewsCount')->from('table.contents'));
    if ($count['viewsCount'] == null) $count['viewsCount'] = 0;
    return $count['viewsCount'];
}

// 获取总点赞数
function agreeCount() {
    $db = Typecho_Db::get();
    $count = $db->fetchRow($db->select('SUM(agree) AS agreeCount')->from('table.contents'));
    if ($count['agreeCount'] == null) $count['agreeCount'] = 0;
    return $count['agreeCount'];
}

// 获取 ECharts 格式要求的文章更新日历
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
        $dateList[] = array(
            $key[$i],
            $dateList2[$key[$i]]
        );
    }

    return $dateList;
}

// 获取 ECharts 格式要求的评论更新日历
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
        $dateList[] = array(
            $key[$i],
            $dateList2[$key[$i]]
        );
    }

    return $dateList;
}

// 获取个分类的文章数量
function categoryPostCount() {
    $db = Typecho_Db::get();
    $count = $db->fetchAll($db->select('name', 'count AS value')->from('table.metas')->where('type = ?', 'category'));
    if (count($count) < 1) {
        return array();
    }
    return $count;
}

// 获取阅读量排名前 5 的 5 篇文章的信息
function top5post() {
    $db = Typecho_Db::get();
    $top5Post = $db->fetchAll($db->select()->from('table.contents')->where('type = ?', 'post')->where('status = ?', 'publish')->order('views', Typecho_Db::SORT_DESC)->offset(0)->limit(5));
    $postList =array();
    foreach ($top5Post as $post) {
        $post = Typecho_Widget::widget('Widget_Abstract_Contents')->filter($post);
        $postList[] = array(
            'title' => $post['title'],
            'link' => $post['permalink'],
            'views' => $post['views']
        );
    }
    return $postList;
}

// 获取评论数排名前 5 的 5 篇文章的信息
function top5CommentPost() {
    $db = Typecho_Db::get();
    $top5Post = $db->fetchAll($db->select()->from('table.contents')->where('type = ?', 'post')->where('status = ?', 'publish')->order('commentsNum', Typecho_Db::SORT_DESC)->offset(0)->limit(5));
    $postList = array();
    foreach ($top5Post as $post) {
        $post = Typecho_Widget::widget('Widget_Abstract_Contents')->filter($post);
        $postList[] = array(
            'title' => $post['title'],
            'link' => $post['permalink'],
            'commentsNum' => $post['commentsNum']
        );
    }
    return $postList;
}

// 获取父评论的姓名
function reply($parent) {
    if ($parent == 0) {
        return '';
    }

    $db = Typecho_Db::get();
    $commentInfo = $db->fetchRow($db->select('author,status,mail')->from('table.comments')->where('coid = ?', $parent));
    $link = '<span class="mx-2">' . $GLOBALS['t']['comment']['reply'] . '</span><b><a class="parent mr-1" href="#comment-' . $parent . '">' . $commentInfo['author'] .  '</a></b>';
    return $link;
}

// 检查数据库字段
function checkField() {
    $db = Typecho_Db::get();
    $prefix = $db->getPrefix();

    // 获取文章表的字段
    $postFields = $db->fetchAll($db->query('PRAGMA table_info(' . $prefix . 'contents)'));
    // 如果阅读量的字段不存在就创建字段
    if (array_search('views', array_column($postFields, 'name')) == false) {
        $db->query('ALTER TABLE `' . $prefix . 'contents` ADD `views` INT(10) NOT NULL DEFAULT 0;');
    }

    // 如果点赞字段不存在就创建字段
    if (array_search('agree', array_column($postFields, 'name')) == false) {
        $db->query('ALTER TABLE `' . $prefix . 'contents` ADD `agree` INT(10) NOT NULL DEFAULT 0;');
    }
}

// 设置文章阅读量
function postViews($archive) {
    // 获取文章的 cid
    $cid = $archive->cid;
    $db = Typecho_Db::get();
    // 查询出阅读量
    $row = $db->fetchRow($db->select('views')->from('table.contents')->where('cid = ?', $cid));
    // 是否是内容页
    if ($archive->is('single')) {
        // 获取阅读 cookie
        $views = Typecho_Cookie::get('extend_contents_views');
        if (empty($views)) {
            $views = array();
        } else {
            $views = explode(',', $views);
        }
        // 如果 cookie 不存在
        if (!in_array($cid, $views)) {
            // 阅读量 +1
            $db->query($db->update('table.contents')->rows(array('views' => (int)$row['views'] + 1))->where('cid = ?', $cid));
            $views[] = $cid;
            $views = implode(',', $views);
            // 写入阅读 cookie
            Typecho_Cookie::set('extend_contents_views', $views);
            // 返回的最终阅读量 +1
            $row['views'] ++;
        }
    }
    return $row['views'];
}

// 检测是否是QQ邮箱
function isQQEmail($email) {
    $re = '/^\d{6,11}\@qq\.com$/';
    preg_match($re, $email, $result);
    if (count($result)) {
        return true;
    }
    return false;
}

// 获取QQ头像
function QQAvatar($email, $name, $size) {
    $qq = str_replace('@qq.com', '', $email);  // 获取QQ号
    $imgUrl = 'https://q2.qlogo.cn/headimg_dl?dst_uin=' . $qq . '&spec=' . $size;
    echo '<img src="' . $imgUrl . '" alt="' . $name . '" class="avatar">';
}

// 评论时间格式化
function commentDateFormat($date, $options = 'format1') {
    // 中文日期
    if ($options == 'format1') {
        return date('Y年m月d日 H:i', $date);
    }
    // - 分隔的日期
    if ($options == 'format2') {
        return date('Y-m-d H:i', $date);
    }
    // 英文日期
    if ($options == 'format3') {
        return date('F jS, Y \a\t h:i a', $date);
    }
    // 时间间隔
    if ($options == 'format4') {
        if ($GLOBALS['language'] == 'en') {
            // 英文
            return formatTimeDifferenceEN($date);
        }else {
            // 中文
            return formatTimeDifferenceZH($date);
        }
    }
}

// 获取时间间隔（中文）
function formatTimeDifferenceZH($timestamp) {
    $timestamp = time() - $timestamp;
    if ($timestamp < 1) {
        return '1秒前';
    }else if ($timestamp < 60) {
        return $timestamp . '秒前';
    }else if ($timestamp > 60 && $timestamp < 3600) {
        return round($timestamp / 60, 0) . '分钟前';
    }else if ($timestamp > 3600 && $timestamp < 86400) {
        return round($timestamp / 3600, 0) . '小时前';
    }else {
        return round($timestamp / 86400, 0) . '天前';
    }
}

// 获取时间间隔（英文）
function formatTimeDifferenceEN($timestamp) {
    $diff = time() - $timestamp;

    if ($diff < 60) {
        return $diff == 1 ? "1 second ago" : "$diff seconds ago";
    }

    $minutes = floor($diff / 60);
    if ($minutes < 60) {
        return $minutes == 1 ? "1 minute ago" : "$minutes minutes ago";
    }

    $hours = floor($minutes / 60);
    if ($hours < 24) {
        return $hours == 1 ? "1 hour ago" : "$hours hours ago";
    }

    $days = floor($hours / 24);
    return $days == 1 ? "1 day ago" : "$days days ago";
}

// 获取文章头图显示设置
function headerImageDisplay($t, $options, $defaultImageUrl) {
    // 在文章列表和文章页显示文章头图
    if ($t->fields->headerImgDisplay == 'post-page-list') {
        return postImg($t, $defaultImageUrl);
    }
    // 在文章列表显示文章头图
    if ($t->fields->headerImgDisplay == 'post-list' && $t->is('index') or $t->fields->headerImgDisplay == 'post-list' && $t->is('archive')) {
        return postImg($t, $defaultImageUrl);
    }
    // 在文章页显示文章头图
    if ($t->fields->headerImgDisplay == 'post-page' && $t->is('post')) {
        return postImg($t, $defaultImageUrl);
    }
    // 使用系统文章头图设置
    if ($t->fields->headerImgDisplay == 'default' or $t->fields->headerImgDisplay == null) {
        // 在首页文章列表显示文章头图
        if (is_array($options) && in_array('home', $options) && $t->is('index')) {
            return postImg($t, $defaultImageUrl);
        }
        // 在分类页、标签页、日期归档页显示文章头图
        if (is_array($options) && in_array('home', $options) && $t->is('archive')) {
            return postImg($t, $defaultImageUrl);
        }
        // 在文章页和独立页显示文章头图
        if (is_array($options) && in_array('post', $options) && $t->is('post') or $t->is('page')) {
            return postImg($t, $defaultImageUrl);
        }
    }
    // 不显示文章头图
    if ($t->fields->headerImgDisplay == 'hide') return false;
    return false;
}

// 根据设置获取文章头图
function postImg($a, $defaultUrl) {
    // 手动输入文章头图
    if ($a->fields->imageSource == 'url' && $a->fields->thumb != '') {
        return $a->fields->thumb;
    }
    // 随机文章头图
    if ($a->fields->imageSource == 'default') {
        return randomHeaderImage($defaultUrl);
    }
    // 默认使用第一张图片作为文章头图
    $img = getPostImg($a);
    return $img;
}

// 获取文章的第一张图片
function getPostImg($archive) {

    $img = array();
    preg_match_all("/<img.*?src=\"(.*?)\".*?\/?>/i", $archive->content, $img);
    if (count($img) > 0 && count($img[0]) > 0) {
        $img_url = $img[1][0];
        return $img_url;
    } else {
        return false;
    }
}

// 获取随机文章头图
function randomHeaderImage($imgUrl) {
    if ($imgUrl == null or $imgUrl == '') return false;
    // 把 URL 按行拆分为数组
    $imgUrl = explode(PHP_EOL, $imgUrl);
    // 删除因为空行生成的数组空值
    $imgUrl = array_filter($imgUrl);
    // 如果只有一个 URL 就直接返回 URL
    if (count($imgUrl) < 2) return $imgUrl[0];
    // 随机返回一个 URL
    return $imgUrl[mt_rand(0, count($imgUrl) - 1)];
}

// 获取文章列表的文章头图样式
function getPostListHeaderImageStyle($postStyle, $optionsStyle) {
    if ($postStyle == 'max' or $postStyle == 'mini') {
        return $postStyle;
    }
    if ($postStyle == 'default' or $postStyle == null) {
        if ($optionsStyle == 'max' or $optionsStyle == 'mini') {
            return $optionsStyle;
        }
        return 'max';
    }
    return 'max';
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

// 根据文章内的标题生成目录
function articleDirectory($content) {
    $re = '#<h(\d)(.*?)>(.*?)</h\d>#im';
    preg_match_all($re, $content, $result);
    if (!is_array($result) or count($result[0]) < 1) {
        return array('content' => $content, 'directory' => null);
    }

    $treeList = array();
    $id = 1;
    foreach ($result[1] as $i => $level) {
        $treeList[$id] = array(
            'id' => $id,
            'parent_id' => 0,
            'level' => $level,
            'name' => trim(strip_tags($result[3][$i])),
            'rand' => mt_rand(1000, 9999)
        );
        $id ++;
    }

    for ($i = 2;$i <= count($treeList);$i ++) {
        $item = $treeList[$i];
        $prevItem = $treeList[$i - 1];
        if ($item['level'] == $prevItem['level']) {
            $treeList[$i]['parent_id'] = $prevItem['parent_id'];
            continue;
        }
        if ($item['level'] > $prevItem['level']) {
            $treeList[$i]['parent_id'] = $prevItem['id'];
            continue;
        }
        $parentId = 0;
        while ($item['level'] <= $prevItem['level']) {
            $parentId = $prevItem['parent_id'];
            if (!isset($treeList[($prevItem['id'] - 1)])) {
                break;
            }
            $prevItem = $treeList[($prevItem['id'] - 1)];
        }
        $treeList[$i]['parent_id'] = $parentId;
    }

    $tree = array();
    foreach ($treeList as $item) {
        if ($item[ 'parent_id' ] != 0 && !isset($treeList[$item['parent_id']])) {
            continue;
        }
        if (isset($treeList[$item['parent_id']])) {
            $treeList[$item['parent_id']]['children'][] = &$treeList[$item['id']];
        } else {
            $tree[] = &$treeList[$item['id']];
        }
    }

    $GLOBALS['directory'] = $treeList;
    $GLOBALS['directoryIndex'] = 1;
    $content = preg_replace_callback($re, function ($matches) {
        $name = urlencode(strip_tags($matches[3]));
        $span = '<span data-title="' . $name . $GLOBALS['directory'][$GLOBALS['directoryIndex']]['rand'] . '" id="' . $name . $GLOBALS['directory'][$GLOBALS['directoryIndex']]['rand'] . '"></span>' . $matches[0];
        $GLOBALS['directoryIndex'] ++;
        return $span;
    }, $content);

    return array(
        'content' => $content,
        'directory' => renderArticleDirectory($tree, '')
    );
}

// 生成目录 HTML
function renderArticleDirectory($tree, $parent = '') {
    $index = 1;
    $ariaLabel = $tree[0]['parent_id'] == 0?'aria-label="' . $GLOBALS['t']['sidebar']['tableOfContents'] . '"':'';
    $htmlStr = '<ul class="article-directory"' . $ariaLabel . '>';
    foreach ($tree as $item) {
        $num = $parent == ''?$index:$parent . '.' . $index;
        $htmlStr .= sprintf('<li><a rel="bookmark" data-directory="%s" class="directory-link" href="#%s">%s</a></li>', urlencode($item['name']) . $item['rand'], urlencode($item['name']) . $item['rand'], '<span class="mr-2 directory-num">' . $num . '</span>' . $item['name']);
        if (isset($item['children']) && count($item['children']) > 0) {
            $htmlStr .= renderArticleDirectory($item['children'], $num);
        }
        $index ++;
    }
    $htmlStr .= '</ul>';
    return $htmlStr;
}

// 获取章节目录设置
function getDirectoryOptions($post, $options) {
    if ($post == 'hide') return false;
    if ($post == 'first' or $post == 'first-title') return $post;
    if ($options == 'first' or $options == 'first-title') return $options;
    return false;
}

// 检测是否是 IE
function isIE() {
    $agent = $_SERVER['HTTP_USER_AGENT'];
    if (preg_match('/MSIE/i', $agent) || preg_match('/Trident/i', $agent)) {
        return true;
    }
    return false;
}

// 把图片的 src 替换为 data-src，用于图片懒加载
function replaceImgSrc($content) {
    $pattern = '/<img(.*?)src(.*?)=(.*?)"(.*?)">/i';
    $replacement = '<img$1data-src$3="$4"$5 class="load-img">';
    return preg_replace($pattern, $replacement, $content);
}

// 获取 Gravatar 头像
function gravatar($email, $size, $gravatarUrl = '', $alt = '') {
    $url = $gravatarUrl . md5(strtolower(trim($email))) . '?s=' . $size;
    if ($gravatarUrl == '' or $gravatarUrl == null) {
        $url = 'https://www.gravatar.com/avatar/' . md5(strtolower(trim($email))) . '?s=' . $size;
    }
    echo '<img src="' . $url . '" alt="' . $alt . '" class="avatar" />';
}

// 获取管理员信息
function getAdminInfo() {
    $db = Typecho_Db::get();
    $userInfo = $db->fetchRow($db->select('mail', 'url', 'screenName', 'created')->from('table.users')->where('group = ?', 'administrator'));
    return $userInfo;
}

// 获取文章列表显示设置
function postListStyle($option, $postOption) {
    // 判断单篇文章的列表显示设置
    if ($postOption == 'summary' or $postOption == 'fullText') {
        return $postOption;
    }
    // 判断列表全局设置
    if ($option == 'fullText' or $option == 'summary') {
        return $option;
    }
    // 如果出现异常就默认显示文章摘要和
    return 'summary';
}