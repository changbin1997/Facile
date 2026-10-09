<?php

/**
 * Facile 主题 - 语言本地化与日期时间
 *
 *
 * 包含函数：
 *  - languageInit                按 Cookie / 浏览器 / 后台设置加载语言包
 *  - detectBrowserLanguage       按浏览器语言偏好检测语言（英文 / 简体 / 繁体）
 *  - parseAcceptLanguage         解析 HTTP Accept-Language 请求头
 *  - localizeScript              输出传给 JS 的多语言翻译
 *  - postDateFormat              文章日期按语言格式化
 *  - getDayWithSuffix            英文日序数后缀
 *  - commentDateFormat           评论日期格式化
 *  - formatTimeDifference        相对时间（文本取自语言包，便于扩展新语言）
 *
 * @package Facile
 */


/**
 * 设置语言
 *
 * @param string $language 语言设置选择的默认语言
 * @return void
 */
function languageInit($language) {
    // 如果有语言设置 Cookie 就优先使用 Cookie 存储的语言
    if (isset($_COOKIE['language']) && $_COOKIE['language'] != '') {
        $language = $_COOKIE['language'];
    }

    // 自动选择
    if ($language == 'auto') {
        $language = detectBrowserLanguage();
    }

    // 选择繁体中文
    if (in_array($language, array('zh-TW', 'zh-HK', 'zh-MO', 'zh-Hant'))) {
        require_once __DIR__ . '/../languages/zh-tw.php';
        $GLOBALS['t'] = ZH_TW;
        $language = 'zh-TW';
    }
    // 选择简体中文
    elseif ($language == 'zh-CN' || $language == 'zh' || $language == null) {
        require_once __DIR__ . '/../languages/zh.php';
        $GLOBALS['t'] = ZH;
        $language = 'zh-CN';
    }
    // 选择英文
    elseif ($language == 'en') {
        require_once __DIR__ . '/../languages/en.php';
        $GLOBALS['t'] = EN;
        $language = 'en';
    }
    // 其它不支持的语言都使用英文
    else {
        require_once __DIR__ . '/../languages/en.php';
        $GLOBALS['t'] = EN;
        $language = 'en';
    }

    $GLOBALS['language'] = $language;
}


/**
 * 根据浏览器的语言偏好自动检测语言
 *
 * 只区分英文、简体中文和繁体中文三种结果：
 *  - 偏好为繁体中文（zh-TW、zh-HK、zh-MO、zh-Hant 等）返回 zh-TW
 *  - 偏好为简体中文（zh-CN、zh-SG、zh-Hans 等）返回 zh-CN
 *  - 偏好为没有标注简繁体的中文（zh）返回 zh-CN
 *  - 偏好与中文无关或者不包含语言偏好返回 en
 *
 * @return string zh-TW / zh-CN / en
 */
function detectBrowserLanguage() {
    // 浏览器没有发送语言偏好就使用英文
    if (empty($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
        return 'en';
    }

    // 按权重（q 值）从高到低解析语言偏好
    $acceptLanguages = parseAcceptLanguage($_SERVER['HTTP_ACCEPT_LANGUAGE']);

    foreach ($acceptLanguages as $langTag) {
        // 不是中文
        if (!preg_match('/^zh(?![a-z])/i', $langTag)) {
            // 英文直接使用英文
            if (preg_match('/^en(?![a-z])/i', $langTag)) {
                return 'en';
            }
            // 其它不支持的语言跳过，继续看后面的语言偏好
            continue;
        }

        // 明确标注文字：Hant 为繁体，Hans 为简体
        if (stripos($langTag, 'hant') !== false) {
            return 'zh-TW';
        }
        if (stripos($langTag, 'hans') !== false) {
            return 'zh-CN';
        }

        // 按地区判断：台港澳使用繁体，中国大陆和新加坡等地使用简体
        if (preg_match('/^zh-(tw|hk|mo)(-|$)/i', $langTag)) {
            return 'zh-TW';
        }
        if (preg_match('/^zh-(cn|sg|my)(-|$)/i', $langTag)) {
            return 'zh-CN';
        }

        // 没有说明是繁体还是简体就使用简体
        return 'zh-CN';
    }

    // 没有匹配到支持的语言就使用英文
    return 'en';
}


/**
 * 解析 HTTP Accept-Language 请求头
 *
 * 把 "zh-TW,zh;q=0.9,en;q=0.8" 这样的字符串解析为按权重降序排列的语言标签数组。
 *
 * @param string $acceptLanguage Accept-Language 请求头
 * @return array 按权重从高到低排列的语言标签
 */
function parseAcceptLanguage($acceptLanguage) {
    $languages = array();
    foreach (explode(',', $acceptLanguage) as $index => $part) {
        $part = trim($part);
        if ($part === '') {
            continue;
        }
        // 拆分语言标签和权重
        $segments = explode(';', $part);
        // 部分浏览器会使用下划线连接地区，统一替换为连字符
        $langTag = str_replace('_', '-', trim($segments[0]));
        if ($langTag === '') {
            continue;
        }
        $quality = 1.0;
        for ($i = 1; $i < count($segments); $i++) {
            $segment = trim($segments[$i]);
            if (stripos($segment, 'q=') === 0) {
                $quality = (float) substr($segment, 2);
            }
        }
        // 权重为 0 表示用户不接受该语言
        if ($quality <= 0) {
            continue;
        }
        $languages[] = array('tag' => $langTag, 'quality' => $quality, 'index' => $index);
    }

    // 按权重从高到低排序，权重相同时保持原有顺序
    usort($languages, function ($a, $b) {
        if ($a['quality'] == $b['quality']) {
            return $a['index'] - $b['index'];
        }
        return $a['quality'] < $b['quality'] ? 1 : -1;
    });

    return array_column($languages, 'tag');
}


/**
 * 把一些支持多语言显示的内容传给 JS 显示
 *
 * @return void
 */
function localizeScript() {
    // 需要传给 JS 的翻译内容
    $t = array(
        'pressEnterToAddTheEmojiToTheCommentInputField' => $GLOBALS['t']['emoji']['pressEnterToAddTheEmojiToTheCommentInputField'],
        'zoomIn' => $GLOBALS['t']['imageLightbox']['zoomIn'],
        'zoomOut' => $GLOBALS['t']['imageLightbox']['zoomOut'],
        'rotateLeft' => $GLOBALS['t']['imageLightbox']['rotateLeft'],
        'rotateRight' => $GLOBALS['t']['imageLightbox']['rotateRight'],
        'closeImage' => $GLOBALS['t']['imageLightbox']['closeImage'],
        'nextImage' => $GLOBALS['t']['imageLightbox']['nextImage'],
        'previousImage' => $GLOBALS['t']['imageLightbox']['previousImage'],
        'copyCode' => $GLOBALS['t']['code']['copyCode'],
        'copySuccess' => $GLOBALS['t']['code']['copySuccess'],
        'copyError' => $GLOBALS['t']['code']['copyError'],
        'cancelReply' => $GLOBALS['t']['comment']['cancelReply'],
        'captchaImageAlt' => $GLOBALS['t']['comment']['captchaImageAlt'],
        'captchaLoadError' => $GLOBALS['t']['comment']['captchaLoadError'],
        'enterThePasswordToViewIt' => $GLOBALS['t']['post']['enterThePasswordToViewIt'],
        'enterYourPassword' => $GLOBALS['t']['post']['enterYourPassword'],
        'submit' => $GLOBALS['t']['post']['submit'],
        'replyTo' => $GLOBALS['t']['comment']['replyTo'],
        'like' => $GLOBALS['t']['post']['like'],
        'categoryDistribution' => $GLOBALS['t']['dataPage']['categoryDistribution'],
        'loadMore' => $GLOBALS['t']['loadMore']['loadMore'],
        'loading' => $GLOBALS['t']['loadMore']['loading'],
        'noDescription' => $GLOBALS['t']['githubPage']['noDescription'],
        'unknown' => $GLOBALS['t']['githubPage']['unknown']
    );
    $t = json_encode($t, JSON_UNESCAPED_UNICODE);
    echo '<script type="text/javascript"> window.t = ' . $t . ' </script>';
}

/**
 * 获取英文的日序数后缀
 *
 * @param int $timestamp 时间戳
 * @return string 英文的日序数后缀
 */
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


/**
 * 评论时间格式化
 *
 * @param int $date 日期时间戳
 * @param string $options 评论日期格式设置
 * @return string 返回格式化后的日期
 */
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
        return formatTimeDifference($date);
    }
}


/**
 * 计算时间间隔
 *
 * 各单位的表述取自语言包中的 comment.timeDifference，
 * 每个单位包含 [单数, 复数] 两个模板，模板里的 %d 会被替换为数量，
 * 这样新增语言时只需要补充语言包，不需要再添加新的函数。
 *
 * @param int $timestamp 时间戳
 * @return string 返回格式化后的时间间隔
 */
function formatTimeDifference($timestamp) {
    $units = $GLOBALS['t']['comment']['timeDifference'];
    $diff = time() - $timestamp;
    // 时间戳在当前时间之后或者相差不到 1 秒时都按 1 秒计算
    if ($diff < 1) {
        $diff = 1;
    }

    if ($diff < 60) {
        return sprintf($diff == 1 ? $units['seconds'][0] : $units['seconds'][1], $diff);
    }

    $minutes = floor($diff / 60);
    if ($minutes < 60) {
        return sprintf($minutes == 1 ? $units['minutes'][0] : $units['minutes'][1], $minutes);
    }

    $hours = floor($minutes / 60);
    if ($hours < 24) {
        return sprintf($hours == 1 ? $units['hours'][0] : $units['hours'][1], $hours);
    }

    $days = floor($hours / 24);
    return sprintf($days == 1 ? $units['days'][0] : $units['days'][1], $days);
}
