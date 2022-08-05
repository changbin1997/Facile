<?php
/**
 * 友情链接
 * @package custom
 */
if (!defined('__TYPECHO_ROOT_DIR__')) exit;
$GLOBALS['page'] = 'page-links';
$linkArr = array();
//  是否包含内页链接
if ($this->options->pageLinks) {
    array_push($linkArr, array(
        'title' => '内页链接',
        'links' => json_decode($this->options->pageLinks)
    ));
}
//  是否包含首页链接
if ($this->options->homeLinks) {
    array_push($linkArr, array(
        'title' => '首页链接',
        'links' => json_decode($this->options->homeLinks)
    ));
}
//  是否包含全站链接
if ($this->options->links) {
    array_push($linkArr, array(
        'title' => '全站链接',
        'links' => json_decode($this->options->links)
    ));
}
$this->need('components/header.php');
?>

<div class="container main">
    <div class="row my-4">
        <div class="col-xl-8 col-lg-8 post-page mb-5 mb-sm-5 mb-md-5 mb-lg-0 mb-xl-0">
            <?php if ($this->options->breadcrumb == 'on'): ?>
                <nav aria-label="路径" class="breadcrumb-nav bg">
                    <ol class="breadcrumb m-0 pl-0 pr-0 pt-0 border-0">
                        <li class="breadcrumb-item">
                            <a href="<?php $this->options->siteUrl(); ?>">首页</a>
                        </li>
                        <li tabindex="0" class="breadcrumb-item active" aria-current="page"><?php $this->title(); ?></li>
                    </ol>
                </nav>
            <?php endif; ?>
            <main class="page">
                <article class="mb-4 border-bottom">
                    <header>
                        <h1 class="post-title m-0">
                            <a href="<?php $this->permalink(); ?>" rel="bookmark"><?php $this->title(); ?></a>
                        </h1>
                    </header>
                    <?php $headerImg = headerImageDisplay($this, $this->options->headerImage, $this->options->headerImageUrl); ?>
                    <?php if ($headerImg): ?>
                        <div class="header-img mb-3 mt-4">
                            <a <?php if ($this->options->headerImageStyle == 'rounded-corners') echo 'class="rounded"'; ?> href="<?php $this->permalink(); ?>" aria-hidden="true" aria-label="文章头图" style="background-image: url(<?php echo $headerImg; ?>);" tabindex="-1"></a>
                        </div>
                    <?php endif; ?>
                    <div class="post-info mt-2">
                        <span class="ml-1" title="发布日期" data-toggle="tooltip" data-placement="top">
                                <i class="icon-calendar mr-1" aria-hidden="true"></i>
                                <?php $this->date('Y年m月d日'); ?>
                            </span>
                        <span class="ml-2" title="作者" data-toggle="tooltip" data-placement="top">
                                <i class="icon-user mr-1" aria-hidden="true"></i>
                                <a href="<?php $this->author->permalink(); ?>" class="mr-2" title="作者：<?php $this->author(); ?>">
                                    <?php $this->author(); ?>
                                </a>
                            </span>
                        <span class="ml-2" title="阅读量" data-toggle="tooltip" data-placement="top">
                                <?php $views = getPostViews($this); ?>
                                <i class="icon-eye mr-1" aria-hidden="true"></i>
                                <?php echo $views; ?>
                            </span>
                    </div>
                    <div class="post-content mt-4" data-code-line-num="<?php $this->options->codeLineNum(); ?>">
                        <?php if (count($linkArr)): ?>
                            <?php foreach ($linkArr as $link): ?>
                                <h2><?php echo $link['title']; ?></h2>
                                <div class="row page-links mb-4 mt-3" aria-label="<?php echo $link['title']; ?>" role="group">
                                    <?php foreach ($link['links'] as $val): ?>
                                        <div class="col-xl-4 col-lg-4 col-md-4 col-sm-6 col-12 link mb-3">
                                            <?php if (isset($val->logoUrl)): ?>
                                                <img class="logo mr-2" src="<?php echo $val->logoUrl; ?>" alt="<?php echo $val->name; ?>">
                                            <?php else: ?>
                                                <div aria-label="<?php echo $val->name; ?>" role="img" class="logo-icon mr-2">
                                                    <i class="icon-link"></i>
                                                </div>
                                            <?php endif; ?>
                                            <a href="<?php echo $val->url; ?>" title="<?php echo isset($val->title)?$val->title:$val->name; ?>" target="_blank" data-toggle="tooltip" data-placement="top">
                                                <?php echo $val->name; ?>
                                            </a>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                                <hr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                        <?php $this->content(); ?>
                    </div>
                </article>
                <?php $this->need('components/comments.php'); ?>
            </main>
        </div>
        <?php $this->need('components/sidebar.php'); ?>
    </div>
</div>

<?php $this->need('components/footer.php'); ?>
