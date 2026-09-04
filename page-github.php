<?php
/**
 * Github项目展示
 * @package custom
 */

if (!defined('__TYPECHO_ROOT_DIR__')) exit;

// 让主题使用的时区跟随 Typecho 设置的时区
setTimezoneByOffset($this->options->timezone);
// 语言初始化
languageInit($this->options->language);

$GLOBALS['page'] = 'page-github';
$this->need('components/header.php');
?>

<div class="container main" id="main">
    <div class="row my-4">
        <div class="col-xl-8 col-lg-8 post-page github-page mb-5 mb-sm-5 mb-md-5 mb-lg-0 mb-xl-0">
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

                    <div class="post-content mt-4">
                        <?php
                        // 添加表格样式
                        $postContent = addBootstrapTableClasses($this->content);
                        // 自定义短代码语法解析
                        $postContent = parseThemeShortcodes($postContent);
                        // 站外链接添加 target="_blank" 与 rel="noopener"
                        $postContent = addExternalLinkAttributes($postContent, $this->options->siteUrl);
                        echo $postContent;
                        ?>
                        <?php if ($this->options->githubUserName): ?>
                            <span id="github-username" style="display: none;" data-user="<?php $this->options->githubUserName(); ?>"></span>
                            <div class="row mb-3" id="repository-list">
                                <div class="col-12 loading-animation mb-3">
                                    <div class="spinner-border spinner-border-sm mr-2" role="status">
                                        <span class="sr-only">Loading...</span>
                                    </div>
                                    <span><?php echo $GLOBALS['t']['loadMore']['loading']; ?></span>
                                </div>
                            </div>
                            <button type="button" class="btn load-more-repository-btn btn-block btn-outline-primary mb-4" style="display: none;">
                                <?php echo $GLOBALS['t']['loadMore']['loadMore']; ?>
                            </button>
                        <?php else: ?>
                            <!--没有填写github用户名-->
                            <div class="mb-3" id="repository-list">
                                <div class="alert alert-info"><?php echo $GLOBALS['t']['githubPage']['githubUsernameIsnotConfigured']; ?></div>
                            </div>
                        <?php endif; ?>
                    </div>

                </article>
                <?php $this->need('components/comments.php'); ?>
            </main>
        </div>
        <?php $this->need('components/sidebar.php'); ?>
    </div>
</div>
<?php $this->need('components/footer.php'); ?>