<?php
if (!defined('__TYPECHO_ROOT_DIR__')) exit;
$GLOBALS['page'] = 'post';
//  点赞请求
if (isset($_POST['agree'])) {
    if ($_POST['agree'] == $this->cid) {
        exit((string)agree($this->cid));
    }
    exit('error');
}

// 语言初始化
languageInit($this->options->language);
// 获取文章底部交互区域的按钮设置
$engagementSection = str_replace(' ', '', $this->options->engagementSection);
if ($engagementSection != '') $engagementSection = explode(',', $engagementSection);

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
                        <li class="breadcrumb-item">
                            <?php $this->category(' '); ?>
                        </li>
                        <li tabindex="0" class="breadcrumb-item active" aria-current="page"><?php $this->title(); ?></li>
                    </ol>
                </nav>
            <?php endif; ?>
            <main class="post">
                <article>
                    <header>
                        <h1 class="post-title m-0">
                            <a href="<?php $this->permalink(); ?>" rel="bookmark">
                                <?php
                                if ($this->hidden) {
                                    echo $GLOBALS['t']['post']['thisPostIsPasswordProtected'];
                                }else {
                                    $this->title();
                                }
                                ?>
                            </a>
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
                            <i class="icon-calendar mr-2" aria-hidden="true"></i>
                            <time datetime="<?php $this->date('c'); ?>"><?php echo postDateFormat($this->created); ?></time>
                        </span>
                        <span class="ml-2" title="<?php echo $GLOBALS['t']['post']['author']; ?>" data-toggle="tooltip" data-placement="top">
                            <i class="icon-user mr-2" aria-hidden="true"></i>
                            <a rel="author" href="<?php $this->author->permalink(); ?>" class="mr-2" title="作者：<?php $this->author(); ?>">
                                <?php $this->author(); ?>
                            </a>
                        </span>
                        <span class="ml-2" title="<?php echo $GLOBALS['t']['post']['views']; ?>" data-toggle="tooltip" data-placement="top">
                            <i class="icon-eye mr-2" aria-hidden="true"></i>
                            <?php echo postViews($this); ?>
                        </span>
                        <?php if ($this->user->hasLogin()): ?>
                        <span class="ml-2">
                            <i class="icon-pencil mr-2" aria-hidden="true"></i>
                            <a href="<?php echo $this->options->siteUrl . 'admin/write-post.php?cid=' . $this->cid; ?>"><?php echo $GLOBALS['t']['post']['edit']; ?></a>
                        </span>
                        <?php endif; ?>
                    </div>
                    <div class="post-content mt-4">
                        <?php if (is_numeric($this->fields->expired) && (int)$this->fields->expired > 0 && $this->created + (int)$this->fields->expired * 86400 < time()): ?>
                            <!--警示信息-->
                            <div class="alert expiration-reminder" role="alert"><?php printf($GLOBALS['t']['post']['warningMessage'], getDays($this->created, time())); ?></div>
                        <?php endif; ?>
                        <?php $GLOBALS['postPage'] = preg_split('/\[-page-]|<p>\[-page-]<\/p>/', $this->content); ?>
                        <?php $postPageNum = isset($_GET['post-page'])?$_GET['post-page']:1; ?>
                        <?php if (!isset($GLOBALS['postPage'][$postPageNum - 1])) $postPageNum = 1; ?>
                        <?php $GLOBALS['post'] = articleDirectory($GLOBALS['postPage'][$postPageNum - 1]); ?>
                        <?php echo $this->options->imagelazyloading == 'on'?replaceImgSrc($GLOBALS['post']['content']):$GLOBALS['post']['content']; ?>
                        <?php if ($this->fields->copyrightNotice != 'hide'): ?>
                        <div class="alert my-4" id="copyright-info">
                            <p class="my-2">
                                <?php printf($GLOBALS['t']['post']['copyrightNotice'][0], '<a href="' . $this->options->siteUrl . '">' . $this->options->title . '</a>') ?>
                            </p>
                            <p class="my-2">
                                <?php printf($GLOBALS['t']['post']['copyrightNotice'][1], '<a href="' . $this->permalink . '">' . $this->permalink . '</a>'); ?>
                            </p>
                            <p class="my-2">
                                <?php echo $GLOBALS['t']['post']['copyrightNotice'][2]; ?>
                            </p>
                        </div>
                        <?php endif; ?>
                    </div>

                    <?php if (count($GLOBALS['postPage']) > 1): ?>
                        <nav aria-label="<?php echo $GLOBALS['t']['pagination']['postContentPagination']; ?>" class="py-3 post-pagination">
                            <ol class="pagination justify-content-center">
                                <?php if ($postPageNum > 1): ?>
                                    <li class="page-item">
                                        <a href="<?php echo $this->permalink . '?post-page=' . ($postPageNum - 1); ?>" class="page-link previous-page" aria-label="<?php echo $GLOBALS['t']['pagination']['previousPage']; ?>" title="<?php echo $GLOBALS['t']['pagination']['previousPage']; ?>" data-toggle="tooltip" data-placement="top">
                                            <i class="icon-chevron-left"></i>
                                        </a>
                                    </li>
                                <?php endif; ?>
                                <?php for ($i = 0;$i < count($GLOBALS['postPage']);$i ++): ?>
                                    <li class="page-item <?php if ($i == $postPageNum - 1) echo 'active'; ?>">
                                        <a href="<?php echo $this->permalink . '?post-page=' . ($i + 1); ?>" class="page-link" <?php if ($i == $postPageNum - 1) echo 'aria-current="page"'; ?>><?php echo $i + 1; ?></a>
                                    </li>
                                <?php endfor; ?>
                                <?php if ($postPageNum < count($GLOBALS['postPage'])): ?>
                                    <li class="page-item">
                                        <a href="<?php echo $this->permalink . '?post-page=' . ($postPageNum + 1); ?>" class="page-link next-page" aria-label="<?php echo $GLOBALS['t']['pagination']['nextPage']; ?>" title="<?php echo $GLOBALS['t']['pagination']['nextPage']; ?>" data-toggle="tooltip" data-placement="top">
                                            <i class="icon-chevron-right"></i>
                                        </a>
                                    </li>
                                <?php endif; ?>
                            </ol>
                        </nav>
                    <?php endif; ?>

                    <div class="category-tag clearfix my-4">
                        <div class="post-category float-left" role="group" aria-label="<?php echo $GLOBALS['t']['post']['category']; ?>">
                            <i class="icon-folder-open mr-1" aria-hidden="true"></i>
                            <?php $this->category(' '); ?>
                        </div>
                        <div class="post-tag float-right" role="group" aria-label="<?php echo $GLOBALS['t']['post']['tag']; ?>">
                            <i class="icon-price-tag mr-1" aria-hidden="true"></i>
                            <?php $this->tags(' ', true, '暂无标签'); ?>
                        </div>
                    </div>
                </article>
                <?php if ($engagementSection != ''): ?>
                    <div class="agree-share mb-4">
                        <div class="text-center">
                            <?php foreach ($engagementSection as $val): ?>
                                <?php if ($val == '点赞'): ?>
                                    <?php $agree = $this->hidden?array('agree' => 0, 'recording' => true):agreeNum($this->cid); ?>
                                    <button type="button" class="btn btn-sm agree-btn mr-2" <?php if ($agree['recording']) echo 'disabled'; ?> data-cid="<?php echo $this->cid; ?>" data-url="<?php $this->permalink(); ?>">
                                        <i class="icon-thumbs-up"></i>
                                        <span class="agree-num"><?php echo $GLOBALS['t']['post']['like']; ?> <?php echo $agree['agree']; ?></span>
                                    </button>
                                <?php endif; ?>
                                <?php if ($val == '打赏'): ?>
                                    <button type="button" class="btn btn-sm mr-2" data-toggle="collapse" data-target="#reward-qr" aria-expanded="false">
                                        <i class="icon-coffee"></i>
                                        <span><?php echo $GLOBALS['t']['post']['donate']; ?></span>
                                    </button>
                                <?php endif; ?>
                                <?php if ($val == '分享'): ?>
                                    <button type="button" class="btn btn-sm mr-2" data-toggle="collapse" data-target="#qr-link" aria-expanded="false" aria-controls="collapseExample">
                                        <i class="icon-share2"></i>
                                        <span><?php echo $GLOBALS['t']['post']['share']; ?></span>
                                    </button>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </div>
                        <?php if (in_array('打赏', $engagementSection)): ?>
                            <!--打赏二维码-->
                            <div class="collapse" id="reward-qr">
                                <div class="mt-4 text-center qr">
                                    <img src="<?php $this->options->rewardQr(); ?>" alt="<?php echo $GLOBALS['t']['post']['QRCode']; ?>">
                                </div>
                            </div>
                        <?php endif; ?>
                        <?php if (in_array('分享', $engagementSection)): ?>
                            <!--分享区域-->
                            <div class="collapse" id="qr-link">
                                <div class="mt-4 qr-link">
                                    <p class="text-center mb-2"><?php echo $GLOBALS['t']['post']['scanTheQRCodeBelowToViewAndShareThisPageOnYourPhone']; ?></p>
                                    <div class="text-center">
                                        <canvas id="qr" class="mb-1" aria-label="<?php echo $GLOBALS['t']['post']['QRCode']; ?>"></canvas>
                                        <div class="link-box">
                                            <a href="https://service.weibo.com/share/share.php?url=<?php $this->permalink(); ?>&title=<?php $this->title(); ?>" target="_blank" rel="external nofollow" aria-label="<?php echo $GLOBALS['t']['post']['shareOnWeibo']; ?>" title="<?php echo $GLOBALS['t']['post']['shareOnWeibo']; ?>" data-toggle="tooltip" data-placement="top">
                                                <i class="icon-sina-weibo mr-1"></i>
                                            </a>
                                            <a class="text-info" href="https://sns.qzone.qq.com/cgi-bin/qzshare/cgi_qzshare_onekey?url=<?php $this->permalink(); ?>&title=<?php $this->title(); ?>&site=<?php $this->options->siteUrl(); ?>&summary=<?php $this->fields->summaryContent?$this->fields->summaryContent():$this->excerpt($this->options->summary, '...'); ?>" target="_blank" rel="external nofollow" aria-label="<?php echo $GLOBALS['t']['post']['shareOnQzone']; ?>" title="<?php echo $GLOBALS['t']['post']['shareOnQzone']; ?>" data-toggle="tooltip" data-placement="top">
                                                <i class="icon-qzone-logo mr-1"></i>
                                            </a>
                                            <a class="text-info" href="https://twitter.com/intent/tweet?url=<?php $this->permalink(); ?>&text=<?php $this->title(); ?>" target="_blank" rel="external nofollow" aria-label="<?php  echo $GLOBALS['t']['post']['shareOnTwitter']; ?>" title="<?php  echo $GLOBALS['t']['post']['shareOnTwitter']; ?>" data-toggle="tooltip" data-placement="top">
                                                <i class="icon-twitter mr-1"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
                <div class="post-navigation border-top border-bottom py-4">
                    <nav class="row">
                        <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6 previous">
                            <div id="previous-post-text"><?php echo $GLOBALS['t']['post']['previousPost']; ?></div>
                            <div class="text-truncate">
                                <?php $this->thePrev('%s', $GLOBALS['t']['post']['none']); ?>
                            </div>
                        </div>
                        <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6 next">
                            <div class="text-lg-right text-xl-right text-md-right" id="next-post-text"><?php echo $GLOBALS['t']['post']['nextPost']; ?></div>
                            <div class="text-lg-right text-xl-right text-md-right next-box text-truncate">
                                <?php $this->theNext('%s', $GLOBALS['t']['post']['none']); ?>
                            </div>
                        </div>
                    </nav>
                </div>
                <?php $this->need('components/comments.php'); ?>
            </main>
        </div>
        <?php $this->need('components/sidebar.php'); ?>
    </div>
    <?php if ($this->options->directoryMobile == 'enable'): ?>
        <button type="button" id="directory-btn" class="btn text-primary rounded-circle border d-block d-sm-block d-md-block d-lg-none d-xl-none" aria-expanded="false" aria-label="<?php echo $GLOBALS['t']['sidebar']['tableOfContents']; ?>" title="<?php echo $GLOBALS['t']['sidebar']['tableOfContents']; ?>">
            <i class="icon-list-ol"></i>
        </button>
        <div id="directory-mobile" class="border rounded shadow" style="display: none;">
            <div class="title-bar border-bottom">
                <h5 class="m-0"><?php echo $GLOBALS['t']['sidebar']['tableOfContents']; ?></h5>
                <button type="button" class="btn btn-sm close-btn" aria-label="<?php echo $GLOBALS['t']['sidebar']['closeTableOfContents']; ?>" title="<?php echo $GLOBALS['t']['sidebar']['closeTableOfContents']; ?>">
                    <i class="icon-cancel-circle"></i>
                </button>
            </div>
            <div class="p-3 directory-list">
                <?php echo $GLOBALS['post']['directory']; ?>
            </div>
        </div>
    <?php endif; ?>
</div>
<?php $this->need('components/footer.php'); ?>