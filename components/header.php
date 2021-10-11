<?php
if (!defined('__TYPECHO_ROOT_DIR__')) exit;

// 检测是否包含主题配色 cookie
if (isset($_COOKIE['themeColor'])) {
    // 根据主题配色 cookie 设置配色
    $GLOBALS['dark'] = $_COOKIE['themeColor'] == 'dark-color'?true:false;
}else {
    // 如果不包含主题配色 cookie 就使用后台设置的默认配色
    $GLOBALS['dark'] = $this->options->themeColor == 'dark'?true:false;
}
?>

<!doctype html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>
        <?php
        $this->archiveTitle(array(
            'category'  =>  _t('分类 %s 下的文章'),
            'search'    =>  _t('包含关键字 %s 的文章'),
            'tag'       =>  _t('标签 %s 下的文章'),
            'author'    =>  _t('%s 发布的文章')
        ), '', ' - ');
        ?>
        <?php $this->options->title(); ?>
        <?php if ($this->is('index')) echo $this->options->tagline; ?>
    </title>
    <link rel="icon" href="<?php echo $this->options->logoUrl?$this->options->logoUrl:$this->options->siteUrl . 'favicon.ico'; ?>" type="image/x-icon">
    <link rel="stylesheet" href="<?php $this->options->themeUrl('assets/css/bootstrap.min.css'); ?>" type="text/css">
    <link rel="stylesheet" href="<?php $this->options->themeUrl('assets/css/style.css'); ?>" type="text/css">
    <link rel="stylesheet" href="<?php $this->options->themeUrl('assets/css/vs2015.css'); ?>" type="text/css">
    <link rel="stylesheet" href="<?php $this->options->themeUrl('assets/css/icon.css'); ?>" type="text/css">
    <!--自定义CSS-->
    <?php if ($this->options->cssCode): ?>
        <style type="text/css">
            <?php $this->options->cssCode(); ?>
        </style>
    <?php endif; ?>
    <?php if ($this->is('post') && $this->fields->keywords): ?>
        <?php $this->header('keywords=' . $this->fields->keywords); ?>
    <?php else: ?>
        <?php $this->header(); ?>
    <?php endif; ?>
    <!--自定义HTML-->
    <?php if ($this->options->headHTML): ?>
        <?php $this->options->headHTML(); ?>
    <?php endif; ?>
</head>
<body class="<?php if ($GLOBALS['dark']) echo 'dark-color'; ?>">
<header class="sticky-top">
    <nav class="navbar navbar-expand-lg <?php echo $GLOBALS['dark']?'navbar-dark bg-dark':'bg-light navbar-light'; ?>">
        <div class="container">
            <a class="navbar-brand" href="<?php $this->options->siteUrl(); ?>"><?php $this->options->title(); ?></a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarColor03" aria-controls="navbarColor03" aria-expanded="false" aria-label="导航菜单">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarColor03">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item <?php if ($this->is('index')) echo 'active'; ?>">
                        <a class="nav-link" href="<?php $this->options->siteUrl(); ?>" <?php if ($this->is('index')) echo 'aria-current="page"'; ?>>首页</a>
                    </li>
                    <?php $this->widget('Widget_Contents_Page_List')->to($pages); ?>
                    <?php while($pages->next()): ?>
                    <li class="nav-item <?php if ($this->is('page', $pages->slug)) echo 'active'; ?>">
                        <a class="nav-link" href="<?php $pages->permalink(); ?>" <?php if ($this->is('page', $pages->slug)) echo 'aria-current="page"'; ?>>
                            <?php $pages->title(); ?>
                        </a>
                    </li>
                    <?php endwhile; ?>
                </ul>
                <form class="form-inline my-2 my-lg-0" action="<?php $this->options->siteUrl(); ?>" method="post" role="search">
                    <div class="input-group">
                        <input class="form-control" type="search" placeholder="搜索" required name="s">
                        <div class="input-group-append">
                            <button class="btn btn-primary my-sm-0" type="submit" aria-label="搜索" title="搜索" data-toggle="tooltip" data-placement="bottom">
                                <i class="icon-search"></i>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </nav>
</header>