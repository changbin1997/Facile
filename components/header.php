<?php
if (!defined('__TYPECHO_ROOT_DIR__')) exit;

// 检测是否包含主题配色 cookie
if (isset($_COOKIE['themeColor'])) {
    // 检测 cookie
    if ($_COOKIE['themeColor'] == 'light-color' or $_COOKIE['themeColor'] == 'dark-color') {
        // 根据主题配色 cookie 设置配色
        $GLOBALS['color'] = $_COOKIE['themeColor'];
    }else {
        // 如果 cookie 内容有问题就使用主题默认配色
        $GLOBALS['color'] = $this->options->themeColor;
    }
}else {
    // 如果不包含主题配色 cookie 就使用后台设置的默认配色
    $GLOBALS['color'] = $this->options->themeColor;
    // 如果设置了跟随系统主题并且浏览器是 IE
    if ($GLOBALS['color'] == 'auto-color' && isIE()) {
        // 默认使用浅色主题
        $GLOBALS['color'] = 'light-color';
    }
}

// 设置代码高亮主题
$codeThemeColor = $this->options->codeThemeColor;
// 如果代码高亮被禁用就不输出代码高亮主题设置
if ($this->options->codeHighlight != 'enable-highlight') {
    $codeThemeColor = 'code-theme-none';
}

// 导航栏自定义链接
$navLinks = null;
if ($this->options->navLinks) $navLinks = json_decode($this->options->navLinks);

// body class
$bodyClass = array(
    // 代码高亮主题
    $codeThemeColor,
    // 开启代码高亮
    $this->options->codeHighlight,
    // 主题配色模式
    $GLOBALS['color']
);
// 如果启用了代码高亮就添加代码块行号显示设置
if ($this->options->codeHighlight == 'enable-highlight') {
    $bodyClass[] = 'line-num-' . $this->options->codeLineNum;
}
// 把 body class 数组转为 string，方便直接输出
$bodyClass = implode(' ', $bodyClass);
?>

<!doctype html>
<html lang="<?php echo $GLOBALS['language']; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>
        <?php
        $this->archiveTitle(array(
            'category' => $GLOBALS['t']['archive']['postsUnderTheCategory'],
            'search' => $GLOBALS['t']['archive']['postsContainingTheKeyword'],
            'tag' => $GLOBALS['t']['archive']['postsTagged'],
            'author' => $GLOBALS['t']['archive']['postsByAuthor']
        ), '', ' - ');
        ?>
        <?php $this->options->title(); ?>
        <?php if ($this->is('index')) echo $this->options->tagline; ?>
    </title>
    <?php if ($this->is('post') && $this->fields->keywords or $this->fields->summaryContent): ?>
        <?php
        $metaContent = array();
        // 如果设置了自定义关键词就显示自定义关键词
        if ($this->fields->keywords) $metaContent['keywords'] = $this->fields->keywords;
        // 如果设置了自定义摘要内容就显示自定义摘要
        if ($this->fields->summaryContent) $metaContent['description'] = $this->fields->summaryContent;
        // 把包含自定义关键词和摘要的数组转为 URL 查询格式
        $metaContent = urldecode(http_build_query($metaContent));
        $this->header($metaContent);
        ?>
    <?php else: ?>
        <?php $this->header(); ?>
    <?php endif; ?>
    <link rel="icon" href="<?php echo $this->options->logoUrl?$this->options->logoUrl:$this->options->siteUrl . 'favicon.ico'; ?>" type="image/x-icon">
    <link rel="stylesheet" href="<?php $this->options->themeUrl('assets/css/bootstrap.css'); ?>" type="text/css">
    <link rel="stylesheet" href="<?php $this->options->themeUrl('assets/css/style.css'); ?>" type="text/css">
    <link rel="stylesheet" href="<?php $this->options->themeUrl('assets/css/icon.css'); ?>" type="text/css">
    <?php localizeScript(); ?>
    <!--自定义CSS-->
    <?php if ($this->options->cssCode): ?>
        <style type="text/css">
            <?php $this->options->cssCode(); ?>
        </style>
    <?php endif; ?>
    <!--自定义HTML-->
    <?php if ($this->options->headHTML): ?>
        <?php $this->options->headHTML(); ?>
    <?php endif; ?>
</head>
<body class="<?php echo $bodyClass; ?>" data-color="<?php echo $GLOBALS['color']; ?>" data-pjax="<?php $this->options->pjax(); ?>">
<?php if ($this->options->pjax == 'on' && $this->options->pjaxProgressBar == 'on'): ?>
<div id="progress-bar" style="display: none;">
    <div id="progress" class="bg-primary" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0"></div>
</div>
<?php endif; ?>
<header class="sticky-top">
    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <?php if ($this->options->navLogoUrl): ?>
                <a class="navbar-brand" href="<?php $this->options->siteUrl(); ?>" title="<?php $this->options->title(); ?>">
                    <img src="<?php $this->options->navLogoUrl(); ?>" alt="<?php $this->options->title(); ?>" height="<?php $this->options->navLogoHeight(); ?>">
                </a>
            <?php else: ?>
                <a class="navbar-brand" href="<?php $this->options->siteUrl(); ?>"><?php $this->options->title(); ?></a>
            <?php endif; ?>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarColor03" aria-controls="navbarColor03" aria-expanded="false" aria-label="<?php echo $GLOBALS['t']['header']['navigationMenu']; ?>">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarColor03">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item <?php if ($this->is('index')) echo 'active'; ?>">
                        <a class="nav-link" href="<?php $this->options->siteUrl(); ?>" <?php if ($this->is('index')) echo 'aria-current="page"'; ?>><?php echo $GLOBALS['t']['header']['home']; ?></a>
                    </li>
                    <?php $this->widget('Widget_Contents_Page_List')->to($pages); ?>
                    <?php while($pages->next()): ?>
                        <li class="nav-item <?php if ($this->is('page', $pages->slug)) echo 'active'; ?>">
                            <a class="nav-link" href="<?php $pages->permalink(); ?>" <?php if ($this->is('page', $pages->slug)) echo 'aria-current="page"'; ?>>
                                <?php $pages->title(); ?>
                            </a>
                        </li>
                    <?php endwhile; ?>
                    <?php if ($this->options->navLinks && is_array($navLinks)): ?>
                        <?php foreach ($navLinks as $link): ?>
                            <li class="nav-item">
                                <a class="nav-link" href="<?php echo $link->url; ?>"><?php echo $link->name; ?></a>
                            </li>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </ul>
                <?php if ($this->options->changeLanguageBtn == 'show'): ?>
                    <div class="navbar-nav mr-1">
                        <div class="nav-item dropdown">
                            <a href="javascript:;" class="nav-link dropdown-toggle" data-toggle="dropdown" aria-expanded="false" aria-label="语言（Language）" title="语言（Language）" role="button">
                                <i class="icon-languages" style="font-size: 15px;"></i>
                                <span class="ml-1 d-xl-none d-lg-none d-md-inline d-sm-inline d-inline">语言（Language）</span>
                            </a>
                            <div class="dropdown-menu">
                                <a href="javascript:;" data-language="zh-CN" class="change-language dropdown-item <?php if ($GLOBALS['language'] == 'zh' or $GLOBALS['language'] == 'zh-CN') echo 'active'; ?>" aria-checked="<?php echo $GLOBALS['language'] == 'zh' or $GLOBALS['language'] == 'zh-CN'; ?>" role="checkbox">简体中文</a>
                                <a href="javascript:;" data-language="en" class="change-language dropdown-item <?php if ($GLOBALS['language'] == 'en') echo 'active'; ?>" aria-checked="<?php echo $GLOBALS['language'] == 'en'; ?>" role="checkbox">English</a>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
                <form class="form-inline my-2 my-lg-0" action="<?php $this->options->siteUrl(); ?>" method="post" role="search">
                    <div class="input-group">
                        <input class="form-control" type="search" placeholder="<?php echo $GLOBALS['t']['header']['search']; ?>" required name="s">
                        <div class="input-group-append">
                            <button class="btn btn-primary my-sm-0" type="submit" aria-label="<?php echo $GLOBALS['t']['header']['search']; ?>" title="<?php echo $GLOBALS['t']['header']['search']; ?>" data-toggle="tooltip" data-placement="bottom">
                                <i class="icon-search"></i>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </nav>
</header>