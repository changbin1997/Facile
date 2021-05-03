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

<div class="container main">
    <div class="row my-4">
        <div class="col-xl-8 col-lg-8 post-page mb-5 mb-sm-5 mb-md-5 mb-lg-0 mb-xl-0">
            <main class="page archive-page">
                <article class="mb-5">
                    <header>
                        <h1 class="post-title m-0">
                            <a href="<?php $this->permalink(); ?>" rel="bookmark"><?php $this->title(); ?></a>
                        </h1>
                    </header>
                    <div class="post-content mt-4">
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
                                $output .= '<div class="archives-item mb-4"><h2>' . date('Y年m月', $archives->created) . '</h2><ul class="archives-list pl-2" aria-label="' . date('Y年m月', $archives->created) . '">';
                            }
                            $output .= '<li>' . date('d日', $archives->created) . ' <a href="' . $archives->permalink . '">' . $archives->title . '</a></li>'; //输出文章
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