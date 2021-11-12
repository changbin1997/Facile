<?php
/**
 * 网站数据
 *
 * @package custom
 */
if (!defined('__TYPECHO_ROOT_DIR__')) exit;
$GLOBALS['page'] = 'page-data';
$this->need('components/header.php');
?>

<div class="container main">
    <div class="row my-4">
        <div class="col-xl-8 col-lg-8 post-page statistics-page mb-5 mb-sm-5 mb-md-5 mb-lg-0 mb-xl-0">
            <main class="page">
                <article class="mb-4 border-bottom">
                    <header>
                        <h1 class="post-title m-0">
                            <a href="<?php $this->permalink(); ?>" rel="bookmark" <?php if ($GLOBALS['dark']) echo 'class="text-light"'; ?>><?php $this->title(); ?></a>
                        </h1>
                    </header>
                    <?php if ($this->options->headerImage && in_array('post', $this->options->headerImage)): ?>
                        <?php $img = postImg($this); ?>
                        <?php if ($img): ?>
                            <div class="header-img mb-3 mt-4">
                                <a href="<?php $this->permalink(); ?>" aria-hidden="true" aria-label="文章头图" style="background-image: url(<?php echo $img; ?>);" tabindex="-1"></a>
                            </div>
                        <?php endif; ?>
                    <?php endif; ?>
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
                        <span class="ml-2" title="阅读量" data-toggle="tooltip" data-placement="top">
                                <?php $views = getPostViews($this); ?>
                                <i class="icon-eye mr-1" aria-hidden="true"></i>
                                <?php echo $views; ?>
                            </span>
                    </div>
                    <div class="post-content mt-4">
                        <h2>基本统计</h2>
                        <p>下面是网站的基本数据统计：</p>
                        <?php Typecho_Widget::widget('Widget_Stat')->to($quantity); ?>
                        <div class="row">
                            <div class="col-lg-4 col-md-4 col-sm-6 col-6 pb-3">
                                <div class="py-2 statistics-card">
                                    <h3 class="text-center"><?php $quantity->publishedPostsNum(); ?></h3>
                                    <h4 class="text-center">文章数</h4>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-6 col-6 pb-3">
                                <div class="py-2 statistics-card">
                                    <h3 class="text-center"><?php $quantity->publishedCommentsNum(); ?></h3>
                                    <h4 class="text-center">评论数</h4>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-6 col-6 pb-3">
                                <div class="py-2 statistics-card">
                                    <h3 class="text-center"><?php echo categoryCount(); ?></h3>
                                    <h4 class="text-center">分类数</h4>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-6 col-6 pb-3">
                                <div class="py-2 statistics-card">
                                    <h3 class="text-center"><?php echo tagCount(); ?></h3>
                                    <h4 class="text-center">标签数</h4>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-6 col-6 pb-3">
                                <div class="py-2 statistics-card">
                                    <h3 class="text-center"><?php echo viewsCount(); ?></h3>
                                    <h4 class="text-center">文章阅读量</h4>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-6 col-6 pb-3">
                                <div class="py-2 statistics-card">
                                    <h3 class="text-center"><?php echo agreeCount(); ?></h3>
                                    <h4 class="text-center">获赞数</h4>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <h2>分类占比</h2>
                        <p>下面是个分类的文章占比：</p>
                        <div id="category-chart">
                            <div class="loading text-center">
                                <h4 class="text-primary">正在加载图表，请稍等……</h4>
                            </div>
                        </div>
                        <hr>
                        <h2>文章更新</h2>
                        <p>下面是 <?php echo date('Y年m月d日', time() - 20736000); ?> 到 <?php echo date('Y年m月d日', time()); ?> 的文章更新情况</p>
                        <div id="post-chart">
                            <div class="loading text-center">
                                <h4 class="text-primary">正在加载图表，请稍等……</h4>
                            </div>
                        </div>
                        <hr>
                        <h2>评论动态</h2>
                        <p>下面是 <?php echo date('Y年m月d日', time() - 20736000); ?> 到 <?php echo date('Y年m月d日', time()); ?> 的评论动态</p>
                        <div id="comment-chart">
                            <div class="loading text-center">
                                <h4 class="text-primary">正在加载图表，请稍等……</h4>
                            </div>
                        </div>
                        <hr>
                        <h2>最多阅读的文章</h2>
                        <?php $top5Post = top5post(); ?>
                        <p>下面是阅读量排名前 <?php echo count($top5Post); ?> 的 <?php echo count($top5Post); ?> 篇文章</p>
                        <table>
                            <thead>
                            <tr>
                                <th>排名</th>
                                <th>文章</th>
                                <th>阅读量</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php $top = 1; ?>
                            <?php foreach ($top5Post as $post): ?>
                                <tr>
                                    <td><?php echo $top; ?></td>
                                    <td><a href="<?php echo $post['link']; ?>"><?php echo $post['title']; ?></a></td>
                                    <td><?php echo $post['views']; ?></td>
                                </tr>
                                <?php $top ++; ?>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                        <hr>
                        <h2>最多评论的文章</h2>
                        <?php $top5CommentPost = top5CommentPost(); ?>
                        <p>下面是评论数排名前 <?php echo count($top5CommentPost); ?> 的 <?php echo count($top5CommentPost); ?> 篇文章：</p>
                        <table>
                            <thead>
                            <tr>
                                <th>排名</th>
                                <th>文章</th>
                                <th>评论数</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php $top = 1; ?>
                            <?php foreach ($top5CommentPost as $post): ?>
                                <tr>
                                    <td><?php echo $top; ?></td>
                                    <td><a href="<?php echo $post['link']; ?>"><?php echo $post['title']; ?></a></td>
                                    <td><?php echo $post['commentsNum']; ?></td>
                                </tr>
                                <?php $top ++; ?>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </article>
                <?php $this->need('components/comments.php'); ?>
            </main>
        </div>
        <?php $this->need('components/sidebar.php'); ?>
    </div>
</div>
<script type="text/javascript">
  var data = {
    post: <?php echo json_encode(postCalendar(time() - 20736000, time())); ?>,
    comment: <?php echo json_encode(commentCalendar(time() - 20736000, time())); ?>,
    category: <?php echo json_encode(categoryPostCount()); ?>
  };
</script>
<?php $this->need('components/footer.php'); ?>
