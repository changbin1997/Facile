<?php
/**
 * 友情链接
 * @package custom
 */
if (!defined('__TYPECHO_ROOT_DIR__')) exit;
$GLOBALS['page'] = 'page-links';

// 语言初始化
languageInit($this->options->language);

$linkArr = array();
//  是否包含内页链接
if ($this->options->pageLinks) {
    $linkArr[] = array(
        'title' => $GLOBALS['t']['linkPage']['linksOnDedicatedPageOnly'],
        'links' => json_decode($this->options->pageLinks)
    );
}
//  是否包含首页链接
if (
    is_array($this->options->linkPageOptions) &&
    in_array('showHomepageOnLinkPage', $this->options->linkPageOptions) &&
    $this->options->homeLinks
) {
    $linkArr[] = array(
        'title' => $GLOBALS['t']['linkPage']['linksOnHomepage'],
        'links' => json_decode($this->options->homeLinks)
    );
}
//  是否包含全站链接
if (
    is_array($this->options->linkPageOptions) &&
    in_array('showSitewideOnLinkPage', $this->options->linkPageOptions) &&
    $this->options->links
) {
    $linkArr[] = array(
        'title' => $GLOBALS['t']['linkPage']['linksOnAllPages'],
        'links' => json_decode($this->options->links)
    );
}
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

                    <div class="post-content mt-4">
                        <?php if (count($linkArr)): ?>
                            <?php foreach ($linkArr as $link): ?>
                                <h2><?php echo $link['title']; ?></h2>
                                <div class="row page-links mb-4 mt-3" aria-label="<?php echo $link['title']; ?>" role="group">
                                    <?php foreach ($link['links'] as $val): ?>
                                        <div class="col-xl-4 col-lg-4 col-md-4 col-sm-6 col-12 link mb-3">
                                            <?php if (isset($val->logoUrl)): ?>
                                                <img class="logo mr-2" src="<?php echo $val->logoUrl; ?>" alt="<?php echo $val->name; ?>">
                                            <?php else: ?>
                                                <div aria-label="<?php echo $val->name; ?>" role="img" class="logo-icon mr-2">
                                                    <i class="icon-link"></i>
                                                </div>
                                            <?php endif; ?>
                                            <a href="<?php echo $val->url; ?>" title="<?php echo isset($val->title)?$val->title:$val->name; ?>" target="_blank" data-toggle="tooltip" data-placement="top">
                                                <?php echo $val->name; ?>
                                            </a>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                                <hr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                        <?php
                        // 添加响应式表格样式
                        $postContent = addBootstrapTableClasses($this->content);
                        // 自定义短代码语法解析
                        $postContent = parseThemeShortcodes($postContent);
                        // 站外链接添加 target="_blank" 与 rel="noopener"
                        $postContent = addExternalLinkAttributes($postContent, $this->options->siteUrl);
                        echo $postContent;
                        ?>
                    </div>
                </article>
                <?php $this->need('components/comments.php'); ?>
            </main>
        </div>
        <?php $this->need('components/sidebar.php'); ?>
    </div>
</div>

<?php $this->need('components/footer.php'); ?>
