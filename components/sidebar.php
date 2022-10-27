<?php
// 读取侧边栏组件
$components = $GLOBALS['page'] == 'post'?$this->options->postPageSidebarComponent:$this->options->sidebarComponent;
// 如果侧边栏组件为空就使用默认设置
if ($components == null or $components == '') {
    $components = '搜索,最新文章,最新回复,文章分类,标签云,文章归档,其它功能,友情链接';
}
// 去除空格
$components = str_replace(' ', '', $components);
// 转为数组
$components = explode(',', $components);
?>

<aside class="col-xl-4 col-lg-4 sidebar pl-4 pl-lg-3">
    <?php foreach ($components as $component): ?>
        <?php if ($component == '博客信息'): ?>
            <!--博客信息-->
            <section class="ml-xl-4 ml-lg-3 mb-5 blog-info">
                <h2 class="mb-4">博客信息</h2>
                <div>
                    <?php if (!$this->options->nickname or !$this->options->birthday or !$this->options->avatarUrl) $userInfo = getAdminInfo(); ?>
                    <div class="blog-user-info">
                        <img src="<?php $this->options->avatarUrl?$this->options->avatarUrl():gravatarUrl($userInfo['mail'], 56); ?>" alt="<?php echo $this->options->nickname?$this->options->nickname . '的头像':$this->options->title . '的头像'; ?>" class="avatar mr-3">
                        <div class="blog-text-info">
                            <h5 class="mb-1"><a href="<?php echo $this->options->nicknameUrl?$this->options->nicknameUrl:$this->options->siteUrl; ?>" target="_blank"><?php echo $this->options->nickname?$this->options->nickname:$userInfo['screenName']; ?></a></h5>
                            <p class="m-0"><?php echo $this->options->Introduction?$this->options->Introduction:$this->options->description; ?></p>
                        </div>
                    </div>
                    <hr>
                    <div class="statistics mb-2">
                        <?php Typecho_Widget::widget('Widget_Stat')->to($quantity); ?>
                        <div class="mb-2">
                            <p><i class="icon-award mr-2"></i> 文章数 <?php $quantity->publishedPostsNum(); ?></p>
                        </div>
                        <div class="mb-2">
                            <p><i class="icon-bubble mr-2"></i> 评论数 <?php $quantity->publishedCommentsNum(); ?></p>
                        </div>
                        <div class="mb-2">
                            <p><i class="icon-eye mr-2"></i> 文章阅读量 <?php echo viewsCount(); ?></p>
                        </div>
                        <div class="mb-2">
                            <p><i class="icon-calendar mr-2"></i> 运行天数 <?php echo $this->options->birthday?round((time() - strtotime($this->options->birthday)) / 86400, 0) . '天':round((time() - $userInfo['created']) / 86400, 0) . '天'; ?></p>
                        </div>
                    </div>
                </div>
            </section>
        <?php endif; ?>
        <?php if ($component == '主题配色'): ?>
            <!--主题配色-->
            <section class="ml-xl-4 ml-lg-3 mb-5 change-color">
                <h2 class="mb-4">主题配色</h2>
                <ul aria-label="主题配色">
                    <li>
                        <div class="custom-control custom-radio">
                            <input class="custom-control-input change-theme-color" type="radio" name="color" id="light-color">
                            <label class="custom-control-label" for="light-color">浅色主题</label>
                        </div>
                    </li>
                    <li>
                        <div class="custom-control custom-radio">
                            <input class="custom-control-input change-theme-color" type="radio" name="color" id="dark-color">
                            <label class="custom-control-label" for="dark-color">深色主题</label>
                        </div>
                    </li>
                </ul>
            </section>
        <?php endif; ?>
        <?php if ($component == '最新文章'): ?>
            <!--最新文章-->
            <section class="ml-xl-4 ml-lg-3 mb-5">
                <h2 class="mb-4">最新文章</h2>
                <ul aria-label="最新文章">
                    <?php $latestArticles = $this->widget('Widget_Contents_Post_Recent'); ?>
                    <?php $postSize = 0; ?>
                    <?php while ($latestArticles->next()): ?>
                        <li>
                            <a href="<?php $latestArticles->permalink(); ?>"><?php $latestArticles->title(); ?></a>
                        </li>
                        <?php
                        $postSize ++;
                        if ($postSize == $this->options->postsListSize) {
                            break;
                        }
                        ?>
                    <?php endwhile; ?>
                </ul>
            </section>
        <?php endif; ?>
        <?php if ($component == '最新回复'): ?>
            <!--最新回复-->
            <section class="ml-xl-4 ml-lg-3 latest-comment mb-5">
                <h2 class="mb-4">最新回复</h2>
                <ul aria-label="最新回复" class="list-unstyled">
                    <?php $this->widget('Widget_Comments_Recent')->to($comments); ?>
                    <?php while($comments->next()): ?>
                        <li class="media mb-2">
                            <?php
                            if ($this->options->QQAvatar == 'show' && isQQEmail($comments->mail)) {
                                QQAvatar($comments->mail, $comments->author, 40);
                            }else {
                                $comments->gravatar('50', '');
                            }
                            if ($comments->type == 'pingback') {
                                echo '<div class="pingback avatar" role="img" aria-label="引用">引用</div>';
                            }
                            ?>
                            <div class="media-body">
                                <h5 class="mb-0 text-truncate">
                                    <a href="<?php $comments->permalink(); ?>" title="发表在 <?php $comments->title(); ?> 的评论" data-toggle="tooltip" data-placement="top">
                                        <?php $comments->author(false); ?>
                                    </a>
                                </h5>
                                <p class="m-0"><?php $comments->excerpt(40, '...'); ?></p>
                            </div>
                        </li>
                    <?php endwhile; ?>
                </ul>
            </section>
        <?php endif; ?>
        <?php if ($component == '文章分类'): ?>
            <!--分类-->
            <section class="ml-xl-4 ml-lg-3 mb-5 category">
                <h2 class="mb-4">文章分类</h2>
                <ul aria-label="文章分类">
                    <?php $this->widget('Widget_Metas_Category_List')->to($category); ?>
                    <?php while ($category->next()): ?>
                        <li <?php if ($category->parent > 0) echo 'class="ml-3"'; ?>>
                            <a title="<?php if ($category->parent > 0) echo getParentCategory($category->parent) . ' 下的子分类 ' ?><?php $category->description(); ?>" href="<?php $category->permalink(); ?>" data-toggle="tooltip" data-placement="top">
                                <?php echo $category->name(); ?>
                                (<?php $category->count(); ?>)
                            </a>
                        </li>
                    <?php endwhile; ?>
                </ul>
            </section>
        <?php endif; ?>
        <?php if ($component == '标签云'): ?>
            <!--标签云-->
            <section class="ml-xl-4 ml-lg-3 tags mb-5">
                <h2 class="mb-4">标签云</h2>
                <?php $limit = $this->options->tagCount == 0?1000:$this->options->tagCount; ?>
                <?php $this->widget('Widget_Metas_Tag_Cloud', 'sort=mid&ignoreZeroCount=1&desc=0&limit=' . $limit)->to($tags); ?>
                <?php if($tags->have()): ?>
                    <div role="group" aria-label="标签云" class="clearfix">
                        <?php
                        $tagsColor = array(
                            'badge-primary',
                            'badge-success',
                            'badge-danger',
                            'badge-warning',
                            'badge-info',
                            'badge-dark'
                        );
                        ?>
                        <?php while ($tags->next()): ?>
                            <a role="listitem" title="<?php $tags->count(); ?> 篇文章" href="<?php $tags->permalink(); ?>" rel="tag" class="p-1 float-left badge m-1 <?php echo $tagsColor[mt_rand(0, 5)]; ?>" data-toggle="tooltip" data-placement="top">
                                <?php $tags->name(); ?>(<?php $tags->count(); ?>)
                            </a>
                        <?php endwhile; ?>
                    </div>
                <?php else: ?>
                <p class="text-center pb-2"><?php _e('没有任何标签'); ?>
                    <?php endif; ?>
            </section>
        <?php endif; ?>
        <?php if ($component == '文章归档'): ?>
            <!--归档-->
            <section class="ml-xl-4 ml-lg-3 mb-5">
                <h2 class="mb-4">文章归档</h2>
                <ul aria-label="文章归档" class="clearfix">
                    <?php $postArchive = $this->widget('Widget_Contents_Post_Date', 'type=month&format=Y年m月'); ?>
                    <?php while ($postArchive->next()): ?>
                        <li class="float-xl-left float-lg-none float-md-left float-sm-left float-left mr-4">
                            <a href="<?php $postArchive->permalink(); ?>" class="mr-2">
                                <?php $postArchive->date(); ?>
                                (<?php $postArchive->count(); ?>)
                            </a>
                        </li>
                    <?php endwhile; ?>
                </ul>
            </section>
        <?php endif; ?>
        <?php if ($component == '其它功能'): ?>
            <!--其它功能-->
            <section class="ml-xl-4 ml-lg-3 mb-5">
                <h2 class="mb-4">其它功能</h2>
                <ul aria-label="其它功能">
                    <?php if ($this->options->loginLink == 'show'): ?>
                        <?php if($this->user->hasLogin()): ?>
                            <li>
                                <a href="<?php $this->options->adminUrl(); ?>"><?php _e('进入后台'); ?> (<?php $this->user->screenName(); ?>)</a>
                            </li>
                            <li>
                                <a href="<?php $this->options->logoutUrl(); ?>"><?php _e('退出'); ?></a>
                            </li>
                        <?php else: ?>
                            <li>
                                <a href="<?php $this->options->adminUrl('login.php'); ?>">登录</a>
                            </li>
                        <?php endif; ?>
                    <?php endif; ?>
                    <li>
                        <a href="<?php $this->options->feedUrl(); ?>"><?php _e('文章 RSS'); ?></a>
                    </li>
                    <li>
                        <a href="<?php $this->options->commentsFeedUrl(); ?>"><?php _e('评论 RSS'); ?></a>
                    </li>
                </ul>
            </section>
        <?php endif; ?>
        <?php if ($component == '友情链接'): ?>
            <!--友情链接-->
            <?php if ($this->options->links or $this->options->homeLinks && $this->is('index')): ?>
                <section class="ml-xl-4 ml-lg-3 mb-5">
                    <h2 class="mb-4">友情链接</h2>
                    <ul aria-label="友情链接">
                        <?php if ($this->options->links): ?>
                            <?php $links = json_decode($this->options->links); ?>
                            <?php foreach ($links as $link): ?>
                                <li>
                                    <a href="<?php echo $link->url; ?>" title="<?php echo isset($link->title)?$link->title:$link->name; ?>" target="_blank" data-toggle="tooltip" data-placement="top">
                                        <?php echo $link->name; ?>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        <?php endif; ?>
                        <?php if ($this->options->homeLinks && $this->is('index')): ?>
                            <?php $links = json_decode($this->options->homeLinks); ?>
                            <?php foreach ($links as $link): ?>
                                <li>
                                    <a href="<?php echo $link->url; ?>" title="<?php echo isset($link->title)?$link->title:$link->name; ?>" target="_blank" data-toggle="tooltip" data-placement="top">
                                        <?php echo $link->name; ?>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </ul>
                </section>
            <?php endif; ?>
        <?php endif; ?>
        <?php if ($component == '目录' && $GLOBALS['page'] == 'post' && $GLOBALS['post']['directory'] != null): ?>
            <section class="ml-xl-4 ml-lg-3 mb-5 directory d-none d-sm-none d-md-none d-lg-block d-xl-block">
                <h2 class="mb-4">目录</h2>
                <?php echo $GLOBALS['post']['directory']; ?>
            </section>
        <?php endif; ?>
    <?php endforeach; ?>
</aside>
