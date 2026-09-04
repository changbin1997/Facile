<?php

/**
 * Facile 主题 - 前台展示辅助
 *
 *
 * 包含函数：
 *  - themeSeoTags                输出 SEO 标签（canonical / noindex）
 *  - bootstrap4Pagination        Bootstrap4 分页
 *  - postListStyle               文章列表显示样式判断
 *  - isIE                        IE 浏览器检测
 *  - getDays                     两个时间戳相差天数
 *
 * @package Facile
 */


/**
 * 输出自定义的 SEO 标签 (Canonical & Noindex)
 *
 * @param object $obj 传入的 $this 对象
 * @param array $seoOptions SEO 相关的设置
 */
function themeSeoTags($obj) {
    $options = Helper::options();
    // 搜索页添加 noindex
    if ($obj->is('search') && $options->searchPageNoindex == 'show') {
        echo '<meta name="robots" content="noindex, follow">';
    }
    // 日期归档页添加 noindex
    if ($obj->is('date') && $options->dateArchivePageNoindex == 'show') {
        echo '<meta name="robots" content="noindex, follow">';
    }
    // 作者归档页添加 noindex
    if ($obj->is('author') && $options->authorPageNoindex == 'show') {
        echo '<meta name="robots" content="noindex, follow">';
    }

    // 输出 canonical 链接
    // 文章页和独立页面
    if ($obj->is('post') || $obj->is('page')) {
        echo '<link rel="canonical" href="' . $obj->permalink . '" />';
    }
    // 获取当前的路由路径信息
    $path = Typecho_Request::getInstance()->getPathInfo();
    $currentUrl = Typecho_Common::url($path, $options->index);
    // 分类和标签归档页
    if ($obj->is('tag') || $obj->is('category')) {
        echo '<link rel="canonical" href="' . $currentUrl . '" />';
    }
    // 首页
    if ($obj->is('index')) {
        if ($path === '/' || empty($path)) {
            echo '<link rel="canonical" href="' . rtrim($options->siteUrl, '/') . '/" />' . "\n";
        } else {
            echo '<link rel="canonical" href="' . $currentUrl . '" />' . "\n";
        }
    }
}


/**
 * 生成 Bootstrap4 分页，并判断是否有下一页
 *
 * @param object $archive 包含 pageNav 方法的 typecho 文章或评论对象
 * @param string $previousPageTitle 用于上一页 title 的文字
 * @param string $nextPageTitle 用于下一页 title 的文字
 * @return bool 有下一页返回 true，否则返回 false（包括没有分页的情况）
 */
function bootstrap4Pagination($archive, $previousPageTitle, $nextPageTitle) {
    ob_start();
    // typecho 分页
    $archive->pageNav('<i class="icon-chevron-left"></i>', '<i class="icon-chevron-right"></i>', 1, '...', array(
        'wrapTag' => 'ul',
        'wrapClass' => 'pagination justify-content-center',
        'itemTag' => 'li',
        'textTag' => 'span',
        'currentClass' => 'active',
        'prevClass' => 'prev',
        'nextClass' => 'next'
    ));
    $content = ob_get_contents();
    ob_end_clean();

    // 如果没有分页则不输出，并返回 false
    if (empty($content)) {
        return false;
    }

    // 给 li 加入 page-item
    $content = preg_replace('/<li(\s+)class="/i', '<li$1class="page-item ', $content);
    $content = preg_replace('/<li>/i', '<li class="page-item">', $content);

    // 给 a 加入 page-link
    $content = preg_replace('/<a href=/', '<a class="page-link" href=', $content);

    // 将 Typecho 默认的 <span> 替换为带类的 <span> (用于当前页高亮和省略号)
    $content = preg_replace('/<span>/', '<span class="page-link">', $content);

    // 为当前激活状态添加 aria-current="page"
    $content = str_replace('<li class="page-item active"><a class="page-link"', '<li class="page-item active"><a aria-current="page" class="page-link"', $content);

    // 给上一页和下一页的链接添加文本提示
    $content = preg_replace_callback(
        '/<a\s+(class="page-link"[^>]*href="[^"]*"[^>]*)><i\s+class="icon-chevron-left"><\/i><\/a>/i',
        function($matches) use ($previousPageTitle) {
            return '<a ' . $matches[1] . ' aria-label="' . $previousPageTitle . '" title="' . $previousPageTitle . '" data-toggle="tooltip" data-placement="top"><i class="icon-chevron-left"></i></a>';
        },
        $content
    );
    $content = preg_replace_callback(
        '/<a\s+(class="page-link"[^>]*href="[^"]*"[^>]*)><i\s+class="icon-chevron-right"><\/i><\/a>/i',
        function($matches) use ($nextPageTitle) {
            return '<a ' . $matches[1] . ' aria-label="' . $nextPageTitle . '" title="' . $nextPageTitle . '" data-toggle="tooltip" data-placement="top"><i class="icon-chevron-right"></i></a>';
        },
        $content
    );

    // 检查是否存在下一页链接（通过查找最终生成的下一页图标）
    $hasNext = (strpos($content, 'icon-chevron-right') !== false);

    echo $content;
    return $hasNext;
}


/**
 * 获取文章列表显示设置
 *
 * @param string $option 文章列表的全局设置
 * @param string $postOption 单篇文章的列表设置
 * @return mixed|string 文章列表显示设置
 */
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


/**
 * 检测是否是 IE
 *
 * @return bool IE 返回 true，不是 IE 返回 false
 */
function isIE() {
    $agent = $_SERVER['HTTP_USER_AGENT'];
    if (preg_match('/MSIE/i', $agent) || preg_match('/Trident/i', $agent)) {
        return true;
    }
    return false;
}


/**
 * 计算两个时间之间相差的天数
 *
 * @param int $time1 时间戳
 * @param int $time2 时间戳
 * @return false|float 返回天数
 */
function getDays($time1, $time2) {
    return floor(($time2 - $time1) / 86400);
}
