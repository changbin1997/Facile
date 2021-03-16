<?php
if (!defined('__TYPECHO_ROOT_DIR__')) exit;
$GLOBALS['page'] = 'page';
$this->need('components/header.php');
?>

    <div class="container main">
        <div class="row my-4">
            <div class="col-xl-8 col-lg-8 post-page mb-5 mb-sm-5 mb-md-5 mb-lg-0 mb-xl-0">
                <main class="page">
                    <article class="mb-4 border-bottom">
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
                    </article>
                    <?php $this->need('components/comments.php'); ?>
                </main>
            </div>
            <?php $this->need('components/sidebar.php'); ?>
        </div>
    </div>
<?php require_once 'components/max-img.php'; ?>
<?php $this->need('components/footer.php'); ?>