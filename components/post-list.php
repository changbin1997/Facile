<?php

while ($this->next()):
?>
<div class="post mb-5 pb-3">
    <article>
        <?php $postListStyle = postListStyle($this->options->postListStyle, $this->fields->postListStyle); ?>
        <!--文章头图区域-->
        <?php $headerImage = headerImageDisplay($this, $this->options->headerImage, $this->options->headerImageUrl); ?>
        <?php if ($postListStyle == 'summary' && getPostListHeaderImageStyle($this->fields->postListHeaderImageStyle, $this->options->postListHeaderImageStyle) == 'max' && $headerImage): ?>
            <div class="header-img mb-4">
                <a <?php if ($this->options->headerImageStyle == 'rounded-corners') echo 'class="rounded"'; ?> href="<?php $this->permalink(); ?>" aria-hidden="true" aria-label="文章头图" style="background-image: url(<?php echo $headerImage; ?>);" tabindex="-1"></a>
            </div>
        <?php endif; ?>
        <div class="post-container">
            <header class="mb-4">
                <h2 class="post-title m-0">
                    <?php $this->sticky(); ?>
                    <a href="<?php $this->permalink(); ?>" rel="bookmark">
                        <?php
                        if ($this->hidden) {
                            echo $GLOBALS['t']['post']['thisPostIsPasswordProtected'];
                        }else {
                            $this->title();
                        }
                        ?>
                    </a>
                </h2>
                <div class="post-info mt-2">
                    <span class="ml-1" title="<?php echo $GLOBALS['t']['post']['publicationDate']; ?>" data-toggle="tooltip" data-placement="top">
                        <i class="icon-calendar mr-2" aria-hidden="true"></i>
                        <time datetime="<?php $this->date('c'); ?>"><?php echo postDateFormat($this->created); ?></time>
                    </span>
                    <span class="ml-2" title="<?php echo $GLOBALS['t']['post']['author']; ?>" data-toggle="tooltip" data-placement="top">
                        <i class="icon-user mr-2" aria-hidden="true"></i>
                        <a href="<?php $this->author->permalink(); ?>" class="mr-2" title="<?php echo $GLOBALS['t']['post']['author']; ?>: <?php $this->author(); ?>">
                            <?php $this->author(); ?>
                        </a>
                    </span>
                    <span class="ml-2" title="<?php echo $GLOBALS['t']['post']['views']; ?>" data-toggle="tooltip" data-placement="top">
                        <i class="icon-eye mr-2"></i>
                        <?php echo postViews($this); ?>
                    </span>
                </div>
            </header>
            <?php if ($postListStyle == 'summary'): ?>
                <?php if (getPostListHeaderImageStyle($this->fields->postListHeaderImageStyle, $this->options->postListHeaderImageStyle) == 'mini' && $headerImage): ?>
                    <div class="post-content mt-4 row" data-header-image-type="<?php echo getPostListHeaderImageStyle($this->fields->postListHeaderImageStyle, $this->options->postListHeaderImageStyle); ?>">
                        <div class="col-xl-9 col-lg-9 col-md-9 col-sm-8 col-7 content-box">
                            <div class="summary-box">
                                <p class="text-color">
                                    <?php
                                    if ($this->hidden) {
                                        echo $GLOBALS['t']['post']['enterThePasswordToViewIt'];
                                    }else {
                                        $this->fields->summaryContent ? $this->fields->summaryContent() : $this->excerpt($this->options->summary, '...');
                                    }
                                    ?>
                                </p>
                            </div>
                            <div class="more-link-wrapper">
                                <div>
                                    <a href="<?php $this->permalink(); ?>" class="btn btn-sm mr-3 read-more">
                                        <?php echo $GLOBALS['t']['post']['readMore']; ?>
                                        <i class="icon-arrow-right2"></i>
                                    </a>
                                    <?php
                                    // 根据使用的语言来调整小头图模式评论数在移动设备的可见性，避免字数超出宽度
                                    $commentCountClass = 'comment-count';
                                    if (mb_strlen($GLOBALS['t']['post']['comments']) > 4) {
                                        $commentCountClass = 'comment-count d-none d-sm-inline d-sm-inline d-lg-inline d-xl-inline';
                                    }
                                    ?>
                                    <a href="<?php $this->permalink() ?>#comments" class="<?php echo $commentCountClass; ?>">
                                        <i class="icon-bubble mr-1"></i>
                                        <b><?php $this->commentsNum('%d ' . $GLOBALS['t']['post']['comments']); ?></b>
                                    </a>
                                </div>
                                <?php if ($this->user->hasLogin()): ?>
                                    <div class="d-none d-sm-block d-md-block d-lg-block d-xl-block">
                                        <a href="<?php echo $this->options->siteUrl . 'admin/write-post.php?cid=' . $this->cid; ?>" class="float-right edit-link">
                                            <i class="icon-pencil mr-1"></i>
                                            <b><?php echo $GLOBALS['t']['post']['edit']; ?></b>
                                        </a>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="col-xl-3 col-lg-3 col-md-3 col-sm-4 col-5 mini-header-image pl-0">
                            <a <?php if ($this->options->headerImageStyle == 'rounded-corners') echo 'class="rounded"'; ?> href="<?php $this->permalink(); ?>" aria-hidden="true" aria-label="文章头图" style="background-image: url(<?php echo $headerImage; ?>);" tabindex="-1"></a>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="post-content mt-4">
                        <p class="text-color">
                            <?php
                            if ($this->hidden) {
                                echo $GLOBALS['t']['post']['enterThePasswordToViewIt'];
                            }else {
                                $this->fields->summaryContent ? $this->fields->summaryContent() : $this->excerpt($this->options->summary, '...');
                            }
                            ?>
                        </p>
                        <div class="more-link-wrapper">
                            <div>
                                <a href="<?php $this->permalink(); ?>" class="btn btn-sm mr-3 read-more">
                                    <?php echo $GLOBALS['t']['post']['readMore']; ?>
                                    <i class="icon-arrow-right2"></i>
                                </a>
                                <a href="<?php $this->permalink() ?>#comments" class="comment-count">
                                    <i class="icon-bubble mr-1"></i>
                                    <b><?php $this->commentsNum('%d ' . $GLOBALS['t']['post']['comments']); ?></b>
                                </a>
                            </div>
                            <?php if ($this->user->hasLogin()): ?>
                                <div class="d-none d-sm-block d-md-block d-lg-block d-xl-block">
                                    <a href="<?php echo $this->options->siteUrl . 'admin/write-post.php?cid=' . $this->cid; ?>" class="float-right edit-link">
                                        <i class="icon-pencil mr-1"></i>
                                        <b><?php echo $GLOBALS['t']['post']['edit']; ?></b>
                                    </a>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endif; ?>
            <?php else: ?>
                <div class="post-content mt-4">
                    <div class="fullText"><?php $this->content(); ?></div>
                    <div class="more-link-wrapper">
                        <div>
                            <a href="<?php $this->permalink(); ?>" class="btn btn-sm mr-3 read-more">
                                <?php echo $GLOBALS['t']['post']['readMore']; ?>
                                <i class="icon-arrow-right2"></i>
                            </a>
                            <a href="<?php $this->permalink() ?>#comments" class="comment-count">
                                <i class="icon-bubble mr-1"></i>
                                <b><?php $this->commentsNum('%d ' . $GLOBALS['t']['post']['comments']); ?></b>
                            </a>
                        </div>
                        <?php if ($this->user->hasLogin()): ?>
                            <div class="d-none d-sm-block d-md-block d-lg-block d-xl-block">
                                <a href="<?php echo $this->options->siteUrl . 'admin/write-post.php?cid=' . $this->cid; ?>" class="float-right edit-link">
                                    <i class="icon-pencil mr-1"></i>
                                    <b><?php echo $GLOBALS['t']['post']['edit']; ?></b>
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </article>
</div>
<?php endwhile; ?>