<?php
/**
 * 文章归档
 *
 * @package custom
 */

if (!defined('__TYPECHO_ROOT_DIR__')) exit;
$GLOBALS['page'] = 'page-archive';
$this->need('components/header.php');
?>

<div class="container main" id="main">
    <div class="row my-4">
        <div class="col-xl-8 col-lg-8 post-page mb-5 mb-sm-5 mb-md-5 mb-lg-0 mb-xl-0">
            <?php if ($this->options->breadcrumb == 'on'): ?>
                <nav aria-label="路径" class="breadcrumb-nav bg">
                    <ol class="breadcrumb m-0 pl-0 pr-0 pt-0 border-0">
                        <li class="breadcrumb-item">
                            <a href="<?php $this->options->siteUrl(); ?>">首页</a>
                        </li>
                        <li tabindex="0" class="breadcrumb-item active" aria-current="page"><?php $this->title(); ?></li>
                    </ol>
                </nav>
            <?php endif; ?>
            <main class="page archive-page">
                <article class="mb-5">
                    <header>
                        <h1 class="post-title m-0">
                            <a href="<?php $this->permalink(); ?>" rel="bookmark"><?php $this->title(); ?></a>
                        </h1>
                    </header>
                    <div class="post-content mt-4">
                        <?php Typecho_Widget::widget('Widget_Stat')->to($quantity); ?>
                        <p>共包含 <?php $quantity->publishedPostsNum(); ?> 篇文章</p>
                        <?php
                        $stat = Typecho_Widget::widget('Widget_Stat');
                        Typecho_Widget::widget('Widget_Contents_Post_Recent', 'pageSize=' . $stat->publishedPostsNum)->to($archives);
                        $year = 0;
                        $mon = 0;
                        $i = 0;
                        $j = 0;
                        $output = '<div class="archives">';
                        while ($archives->next()) {
                            $year_tmp = date('Y', $archives->created);
                            $mon_tmp = date('m', $archives->created);
                            $y = $year;
                            $m = $mon;
                            if ($year > $year_tmp || $mon > $mon_tmp) {
                                $output .= '</ul></div>';
                            }
                            if ($year != $year_tmp || $mon != $mon_tmp) {
                                $year = $year_tmp;
                                $mon = $mon_tmp;
                                // 输出年份
                                $output .= '<div class="archives-item"><h2>' . date('Y年m月', $archives->created) . '</h2><ul class="archives-list pl-2" aria-label="' . date('Y年m月', $archives->created) . '">';
                            }
                            $output .= '<li><span class="day">' . date('d日', $archives->created) . '</span><div class="timeline"></div><div class="link-box"><a href="' . $archives->permalink . '">' . $archives->title . '</a></div></li>'; //输出文章
                        }
                        $output .= '</ul></div></div>';
                        echo $output;
                        ?>
                    </div>
                </article>
            </main>
        </div>
        <?php $this->need('components/sidebar.php'); ?>
    </div>
</div>

<?php $this->need('components/footer.php'); ?>