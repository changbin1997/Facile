<?php
if (!defined('__TYPECHO_ROOT_DIR__')) exit;
$GLOBALS['page'] = 'archive';

// 语言初始化
languageInit($this->options->language);

$this->need('components/header.php');
?>

<div class="container main" id="main">
    <div class="row mt-4">
        <div class="col-xl-8 col-lg-8 post-list">
            <?php if ($this->options->breadcrumb == 'on'): ?>
                <nav aria-label="路径" class="breadcrumb-nav bg">
                    <ol class="breadcrumb m-0 pl-0 pr-0 pt-0 border-0">
                        <li class="breadcrumb-item">
                            <a href="<?php $this->options->siteUrl(); ?>">首页</a>
                        </li>
                        <li tabindex="0" class="breadcrumb-item active" aria-current="page"><?php $this->archiveTitle(' &raquo; ','',''); ?></li>
                    </ol>
                </nav>
            <?php endif; ?>
            <header class="archive-title mb-5">
                <h1>
                    <?php $this->archiveTitle(array(
                        'category' => $GLOBALS['t']['archive']['postsUnderTheCategory'],
                        'search' => $GLOBALS['t']['archive']['postsContainingTheKeyword'],
                        'tag' => $GLOBALS['t']['archive']['postsTagged'],
                        'author' => $GLOBALS['t']['archive']['postsByAuthor']
                    ), '', ''); ?>
                </h1>
                <?php if ($this->getDescription() != ''): ?>
                    <span class="archive-description"><?php echo $this->getDescription(); ?></span>
                <?php endif; ?>
            </header>
            <?php if ($this->have()): ?>
                <?php $this->need('components/post-list.php'); ?>
            <?php else: ?>
                <article class="no-content">
                    <hr>
                    <h4 class="mb-3" role="alert"><?php printf($GLOBALS['t']['archive']['noPostsFoundContaining'], '<b>' . $this->archiveTitle . '</b>') ?></h4 >
                    <p><?php echo $GLOBALS['t']['archive']['youCanTryTheFollowing']; ?></p>
                    <ol class="pl-3 mb-5">
                        <li><?php echo $GLOBALS['t']['archive']['trySearchingWithDifferentKeywords']; ?></li>
                        <li><?php echo $GLOBALS['t']['archive']['browsePostsByCategoryInTheSectionToTheRightOrBelow']; ?></li>
                        <li><?php echo $GLOBALS['t']['archive']['browsePostsByTagsInTheTagCloudSectionToTheRightOrBelow']; ?></li>
                    </ol>
                </article>
            <?php endif; ?>
            <nav class="page-nav my-5" aria-label="分页导航">
                <?php $this->pageNav('<i class="icon-chevron-left"></i>', '<i class="icon-chevron-right"></i>', 1, '...', array('wrapTag' => 'ul', 'wrapClass' => 'pagination justify-content-center', 'itemTag' => 'li', 'textTag' => 'a', 'currentClass' => 'active', 'prevClass' => 'prev', 'nextClass' => 'next')); ?>
            </nav>
        </div>
        <?php $this->need('components/sidebar.php'); ?>
    </div>
</div>

<?php $this->need('components/footer.php'); ?>