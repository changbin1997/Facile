<?php
if (!defined('__TYPECHO_ROOT_DIR__')) exit;
$GLOBALS['page'] = 'post';
//  点赞请求
if (isset($_POST['agree'])) {
    if ($_POST['agree'] == $this->cid) {
        exit(agree($this->cid));
    }
    exit('error');
}

$this->need('components/header.php');
?>

<div class="container main">
    <div class="row my-4">
        <div class="col-xl-8 col-lg-8 post-page mb-5 mb-sm-5 mb-md-5 mb-lg-0 mb-xl-0">
            <main class="post">
                <article>
                    <header>
                        <h1 class="post-title m-0">
                            <a href="<?php $this->permalink(); ?>" rel="bookmark"><?php $this->title(); ?></a>
                        </h1>
                    </header>
                    <?php if ($this->options->headerImage && in_array('post', $this->options->headerImage)): ?>
                        <?php $img = postImg($this); ?>
                        <?php if ($img): ?>
                            <div class="header-img mb-3 mt-4">
                                <a href="<?php $this->permalink(); ?>" aria-hidden="true" aria-label="文章头图" style="background-image: url(<?php echo $img; ?>);" tabindex="-1"></a>
                            </div>
                        <?php endif; ?>
                    <?php endif; ?>
                    <div class="post-info mt-2">
                        <span class="ml-1" title="发布日期" data-toggle="tooltip" data-placement="top">
                            <i class="icon-calendar mr-1"></i>
                            <?php $this->date('Y年m月d日'); ?>
                        </span>
                        <span class="ml-2" title="作者" data-toggle="tooltip" data-placement="top">
                            <i class="icon-user mr-1"></i>
                            <a href="<?php $this->author->permalink(); ?>" class="mr-2" title="作者：<?php $this->author(); ?>">
                                <?php $this->author(); ?>
                            </a>
                        </span>
                        <span class="ml-2" title="阅读量" data-toggle="tooltip" data-placement="top">
                            <?php $views = getPostViews($this); ?>
                            <i class="icon-eye mr-1"></i>
                            <?php echo $views; ?>
                        </span>
                    </div>
                    <div class="post-content mt-4">
                        <?php $this->content(); ?>
                    </div>
                    <div class="category-tag clearfix my-4">
                        <div class="post-category float-left">
                            <i class="icon-folder-open mr-1"></i>
                            <?php $this->category(' '); ?>
                        </div>
                        <div class="post-tag float-right" role="group" aria-label="标签">
                            <i class="icon-price-tag mr-1"></i>
                            <?php $this->tags(' ', true, '暂无标签'); ?>
                        </div>
                    </div>
                </article>
                <div class="agree-share mb-4">
                    <div class="text-center">
                        <?php $agree = $this->hidden?array('agree' => 0, 'recording' => true):agreeNum($this->cid); ?>
                        <button type="button" class="btn btn-outline-primary btn-sm agree-btn" <?php if ($agree['recording']) echo 'disabled'; ?> data-cid="<?php echo $this->cid; ?>" data-url="<?php $this->permalink(); ?>">
                            <i class="icon-thumbs-up"></i>
                            <span class="agree-num">赞 <?php echo $agree['agree']; ?></span>
                        </button>
                        <span class="pl-2"></span>
                        <button type="button" class="btn btn-outline-primary btn-sm" data-toggle="collapse" data-target="#qr-link" aria-expanded="false" aria-controls="collapseExample">
                            <i class="icon-share2"></i>
                            <span>分享</span>
                        </button>
                    </div>
                    <div class="collapse" id="qr-link">
                        <div class="mt-4 qr-link">
                            <p class="text-center mb-2">用手机扫描下方二维码可在手机上浏览和分享</p>
                            <div class="clearfix">
                                <canvas id="qr" class="float-left"></canvas>
                                <div class="link-box float-left p-2">
                                    <a href="https://service.weibo.com/share/share.php?url=<?php $this->permalink(); ?>&title=<?php $this->title(); ?>" class="btn btn-primary d-block mb-2" target="_blank">
                                        <i class="icon-sina-weibo mr-1"></i>
                                        <span>分享到新浪微博</span>
                                    </a>
                                    <a href="https://sns.qzone.qq.com/cgi-bin/qzshare/cgi_qzshare_onekey?url=<?php $this->permalink(); ?>&title=<?php $this->title(); ?>&site=<?php $this->options->siteUrl(); ?>&summary=<?php $this->fields->summaryContent?$this->fields->summaryContent():$this->excerpt($this->options->summary, '...'); ?>" class="btn btn-info d-block mb-2" target="_blank">
                                        <i class="icon-qzone-logo mr-1"></i>
                                        <span>分享到QQ空间</span>
                                    </a>
                                    <a href="https://twitter.com/intent/tweet?url=<?php $this->permalink(); ?>&text=<?php $this->title(); ?>" class="btn btn-info d-block" target="_blank">
                                        <i class="icon-twitter mr-1"></i>
                                        <span>分享到Twitter</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="post-navigation border-top border-bottom py-4">
                    <nav class="row">
                        <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6 previous">
                            <div>上一篇</div>
                            <div class="text-truncate">
                                <?php $this->thePrev('%s','没有了'); ?>
                            </div>
                        </div>
                        <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6 next">
                            <div class="text-lg-right text-xl-right text-md-right">下一篇</div>
                            <div class="text-lg-right text-xl-right text-md-right next-box text-truncate">
                                <?php $this->theNext('%s','没有了'); ?>
                            </div>
                        </div>
                    </nav>
                </div>
                <?php $this->need('components/comments.php'); ?>
            </main>
        </div>
        <?php $this->need('components/sidebar.php'); ?>
    </div>
</div>
<?php require_once 'components/max-img.php'; ?>
<?php $this->need('components/footer.php'); ?>