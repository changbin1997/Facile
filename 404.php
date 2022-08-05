<?php
if (!defined('__TYPECHO_ROOT_DIR__')) exit;
$GLOBALS['page'] = '404';
$this->need('components/header.php');
?>

<div class="container main page-404">
    <div class="mt-5" role="alert" aria-labelledby="page-title" aria-describedby="page-info">
        <h1 class="text-404 text-center" id="page-title">404</h1>
        <h3 class="text-center" id="page-info">您访问的页面不存在！</h3>
    </div>
    <div class="mt-5 row">
        <div class="col-xl-6 offset-xl-3 col-lg-6 offset-lg-3 col-md-8 offset-md-2 col-sm-10 offset-sm-1 col-12">
            <form action="<?php $this->options->siteUrl(); ?>" method="post" role="search">
                <div class="input-group">
                    <input class="form-control" type="search" placeholder="搜索" required name="s">
                    <div class="input-group-append">
                        <button class="btn btn-primary my-sm-0" type="submit" aria-label="搜索" title="搜索" data-toggle="tooltip" data-placement="bottom">
                            <i class="icon-search"></i>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="text-center mt-3">
        <a href="<?php $this->options->siteUrl(); ?>" class="btn" id="back-home-page">返回首页</a>
    </div>
</div>

<?php $this->need('components/footer.php'); ?>