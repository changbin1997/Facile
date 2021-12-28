<?php
if (!defined('__TYPECHO_ROOT_DIR__')) exit;
$GLOBALS['page'] = 'archive';
$this->need('components/header.php');
?>

<div class="container main">
    <div class="row mt-4">
        <div class="col-xl-8 col-lg-8 post-list">
            <header class="archive-title mb-5">
                <h1 <?php if ($GLOBALS['dark']) echo 'class="text-light"'; ?>>
                    <?php $this->archiveTitle(array(
                        'category' => _t('分类 %s 下的文章'),
                        'search' => _t('包含关键字 %s 的文章'),
                        'tag' => _t('标签 %s 下的文章'),
                        'author' => _t('%s 发布的文章')
                    ), '', ''); ?>
                </h1>
                <?php if ($this->getDescription() != ''): ?>
                    <span class="archive-description"><?php echo $this->getDescription(); ?></span>
                <?php endif; ?>
            </header>
            <?php if ($this->have()): ?>
                <?php $this->need('components/post-list.php'); ?>
            <?php else: ?>
                <article>
                    <h2 class="text-center" role="alert">没有查找到您需要的内容！</h2>
                </article>
            <?php endif; ?>
            <nav class="page-nav my-5" aria-label="分页导航">
                <?php $this->pageNav('&laquo;', '&raquo;', 1, '...', array('wrapTag' => 'ul', 'wrapClass' => 'pagination justify-content-center', 'itemTag' => 'li', 'textTag' => 'a', 'currentClass' => 'active', 'prevClass' => 'prev', 'nextClass' => 'next')); ?>
            </nav>
        </div>
        <?php $this->need('components/sidebar.php'); ?>
    </div>
</div>

<?php $this->need('components/footer.php'); ?>