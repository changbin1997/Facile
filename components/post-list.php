<?php
// 设置标题配色
$postTitleColorClass = $GLOBALS['dark']?'class="text-light"':'';
// 设置阅读全文按钮颜色
$readMoreBtnColor = $GLOBALS['dark']?'btn-primary':'btn-outline-primary';

while ($this->next()):
?>
<div class="post mb-5 pb-3">
    <article>
        <!--文章头图区域-->
        <?php if ($this->options->headerImage && in_array('home', $this->options->headerImage)): ?>
            <?php $img = postImg($this); ?>
            <?php if ($img): ?>
                <div class="header-img mb-4">
                    <a <?php if ($this->options->headerImageStyle == 'rounded-corners') echo 'class="rounded"'; ?> href="<?php $this->permalink(); ?>" aria-hidden="true" aria-label="文章头图" style="background-image: url(<?php echo $img; ?>);" tabindex="-1"></a>
                </div>
            <?php endif; ?>
        <?php endif; ?>
        <div class="post-container">
            <header class="mb-4">
                <h2 class="post-title m-0">
                    <?php $this->sticky(); ?>
                    <a href="<?php $this->permalink(); ?>" rel="bookmark" <?php echo $postTitleColorClass; ?>>
                        <?php $this->title(); ?>
                    </a>
                </h2>
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
                </div>
            </header>
            <div class="post-content mt-4">
                <p class="text-color">
                    <?php $this->fields->summaryContent?$this->fields->summaryContent():$this->excerpt($this->options->summary, '...'); ?>
                </p>
                <div class="more-link-wrapper">
                    <div>
                        <a href="<?php $this->permalink(); ?>" class="btn btn-sm mr-3 <?php echo $readMoreBtnColor; ?>">
                            阅读全文
                            <i class="icon-arrow-right2"></i>
                        </a>
                        <a href="<?php $this->permalink() ?>#comments" class="comment-count">
                            <i class="icon-bubble mr-1"></i>
                            <b><?php $this->commentsNum('%d 评论'); ?></b>
                        </a>
                    </div>
                    <?php if ($this->user->hasLogin()): ?>
                        <div>
                            <a href="<?php echo $this->options->siteUrl . 'admin/write-post.php?cid=' . $this->cid; ?>" class="float-right edit-link">
                                <i class="icon-pencil mr-1"></i>
                                <b>编辑</b>
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </article>
</div>
<?php endwhile; ?>