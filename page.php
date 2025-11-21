<?php
if (!defined('__TYPECHO_ROOT_DIR__')) exit;
$GLOBALS['page'] = 'page';

// 语言初始化
languageInit($this->options->language);
$this->need('components/header.php');
?>

<div class="container main" id="main">
    <div class="row my-4">
        <div class="col-xl-8 col-lg-8 post-page mb-5 mb-sm-5 mb-md-5 mb-lg-0 mb-xl-0">
            <?php if ($this->options->breadcrumb == 'on'): ?>
                <nav aria-label="<?php echo $GLOBALS['t']['breadcrumb']; ?>" class="breadcrumb-nav bg">
                    <ol class="breadcrumb m-0 pl-0 pr-0 pt-0 border-0">
                        <li class="breadcrumb-item">
                            <a href="<?php $this->options->siteUrl(); ?>"><?php echo $GLOBALS['t']['header']['home']; ?></a>
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
                        <span class="ml-1" title="<?php echo $GLOBALS['t']['post']['publicationDate']; ?>" data-toggle="tooltip" data-placement="top">
                            <i class="icon-calendar mr-1" aria-hidden="true"></i>
                            <time datetime="<?php $this->date('c'); ?>"><?php echo postDateFormat($this->created); ?></time>
                        </span>
                            <span class="ml-2" title="<?php echo $GLOBALS['t']['post']['author']; ?>" data-toggle="tooltip" data-placement="top">
                            <i class="icon-user mr-1" aria-hidden="true"></i>
                            <a href="<?php $this->author->permalink(); ?>" class="mr-2" title="<?php echo $GLOBALS['t']['post']['author']; ?>: <?php $this->author(); ?>">
                                <?php $this->author(); ?>
                            </a>
                        </span>
                            <span class="ml-2" title="<?php echo $GLOBALS['t']['post']['views']; ?>" data-toggle="tooltip" data-placement="top">
                            <i class="icon-eye mr-1" aria-hidden="true"></i>
                            <?php echo postViews($this); ?>
                        </span>
                        <?php if ($this->user->hasLogin()): ?>
                            <span class="ml-2">
                        <i class="icon-pencil mr-2" aria-hidden="true"></i>
                        <a href="<?php echo $this->options->adminUrl . 'write-page.php?cid=' . $this->cid; ?>"><?php echo $GLOBALS['t']['post']['edit']; ?></a>
                    </span>
                        <?php endif; ?>
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
<?php $this->need('components/footer.php'); ?>