<?php
if (!defined('__TYPECHO_ROOT_DIR__')) exit;
$GLOBALS['page'] = 'archive';
$this->need('components/header.php');
?>

<div class="container main">
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
                <article class="no-content">
                    <hr>
                    <h4 class="mb-3" role="alert">无法查找到包含 <b><?php $this->archiveTitle(array('search' => '%s'), '', ''); ?></b> 的文章！</h4 >
                    <p>您可以尝试：</p>
                    <ol class="pl-3 mb-5">
                        <li>更换关键字重新搜索</li>
                        <li>在右侧或下方的文章分类区域选择分类查找</li>
                        <li>在右侧或下方的标签云区域选择标签查找</li>
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