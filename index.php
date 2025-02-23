<?php
/**
 * 这是一套简洁的博客主题 <a href="https://facile.misterma.com/" target="_blank">点击查看使用说明</a>
 *
 * @package Facile
 * @author Mr. Ma
 * @version 开发板（暂无版本号）
 * @link https://www.misterma.com
 */

if (!defined('__TYPECHO_ROOT_DIR__')) exit;
$GLOBALS['page'] = 'index';

// 语言初始化
languageInit($this->options->language);
// 检查数据库字段
checkField();
$this->need('components/header.php');
?>

<div class="container main" id="main">
    <div class="row mt-4">
        <div class="col-xl-8 col-lg-8 post-list">
            <?php if ($this->have()): ?>
            <?php $this->need('components/post-list.php'); ?>
            <nav class="page-nav my-5" aria-label="<?php echo $GLOBALS['t']['pagination']['pagination']; ?>">
                <?php $this->pageNav('<i class="icon-chevron-left"></i>', '<i class="icon-chevron-right"></i>', 1, '...', array('wrapTag' => 'ul', 'wrapClass' => 'pagination justify-content-center', 'itemTag' => 'li', 'textTag' => 'a', 'currentClass' => 'active', 'prevClass' => 'prev', 'nextClass' => 'next')); ?>
            </nav>
            <?php else: ?>
                <article class="no-content">
                    <h4 class="text-center mb-3" role="alert">没有可以显示的文章</h4>
                </article>
            <?php endif; ?>    
        </div>
        <?php $this->need('components/sidebar.php'); ?>
    </div>
</div>

<?php $this->need('components/footer.php'); ?>