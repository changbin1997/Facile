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

<aside class="col-xl-4 col-lg-4 sidebar pl-lg-3 pl-xl-3">
    <?php foreach ($components as $component): ?>
        <?php if ($component == '博客信息'): ?>
            <!--博客信息-->
            <section class="ml-xl-4 ml-lg-3 mb-5 blog-info">
                <h2 class="mb-4"><?php echo $GLOBALS['t']['sidebar']['blogInfo']; ?></h2>
                <div>
                    <?php if (!$this->options->nickname or !$this->options->birthday or !$this->options->avatarUrl) $userInfo = getAdminInfo(); ?>
                    <div class="blog-user-info">
                        <?php
                            $avatarName = $this->options->nickname?$this->options->nickname . '的头像':$this->options->title . '的头像';
                            if ($this->options->avatarUrl) {
                                echo '<img src="' . $this->options->avatarUrl . '" alt="' . $avatarName . '" class="avatar" />';
                            }else {
                                gravatar($userInfo['mail'], 56, $this->options->gravatarUrl, $avatarName);
                            }
                        ?>
                        <div class="blog-text-info ml-3">
                            <h5 class="mb-1"><a aria-describedby="blog-description" href="<?php echo $this->options->nicknameUrl?$this->options->nicknameUrl:$this->options->siteUrl; ?>" target="_blank"><?php echo $this->options->nickname?$this->options->nickname:$userInfo['screenName']; ?></a></h5>
                            <p id="blog-description" class="m-0"><?php echo $this->options->Introduction?$this->options->Introduction:$this->options->description; ?></p>
                        </div>
                    </div>
                    <hr>
                    <div class="statistics mb-2">
                        <?php Typecho_Widget::widget('Widget_Stat')->to($quantity); ?>
                        <div class="mb-2">
                            <p><i class="icon-award mr-2"></i> <?php printf($GLOBALS['t']['sidebar']['totalPosts'], $quantity->publishedPostsNum); ?></p>
                        </div>
                        <div class="mb-2">
                            <p><i class="icon-bubble mr-2"></i> <?php printf($GLOBALS['t']['sidebar']['totalComments'], $quantity->publishedCommentsNum); ?></p>
                        </div>
                        <div class="mb-2">
                            <p><i class="icon-eye mr-2"></i> <?php printf($GLOBALS['t']['sidebar']['totalViews'], viewsCount()); ?></p>
                        </div>
                        <div class="mb-2">
                            <?php $runningSince = $this->options->birthday ? round((time() - strtotime($this->options->birthday)) / 86400, 0) : round((time() - $userInfo['created']) / 86400, 0); ?>
                            <p><i class="icon-calendar mr-2"></i> <?php printf($GLOBALS['t']['sidebar']['runningSince'], $runningSince); ?></p>
                        </div>
                    </div>
                </div>
            </section>
        <?php endif; ?>

        <?php if ($component == '主题配色'): ?>
            <!--主题配色-->
            <section class="ml-xl-4 ml-lg-3 mb-5 change-color">
                <h2 class="mb-4"><?php echo $GLOBALS['t']['sidebar']['themeColor']; ?></h2>
                <ul aria-label="<?php echo $GLOBALS['t']['sidebar']['themeColor']; ?>">
                    <li>
                        <div class="custom-control custom-radio">
                            <input class="custom-control-input change-theme-color" type="radio" name="color" id="light-color">
                            <label class="custom-control-label" for="light-color"><?php echo $GLOBALS['t']['sidebar']['lightTheme']; ?></label>
                        </div>
                    </li>
                    <li>
                        <div class="custom-control custom-radio">
                            <input class="custom-control-input change-theme-color" type="radio" name="color" id="dark-color">
                            <label class="custom-control-label" for="dark-color"><?php echo $GLOBALS['t']['sidebar']['darkTheme']; ?></label>
                        </div>
                    </li>
                </ul>
            </section>
        <?php endif; ?>

        <?php if ($component == '语言选择'): ?>
            <!--语言选择-->
            <section class="ml-xl-4 ml-lg-3 mb-5 language-select">
                <h2 class="mb-4">语言（Language）</h2>
                <ul aria-label="语言（Language）">
                    <li>
                        <div class="custom-control custom-radio">
                            <input <?php if ($GLOBALS['language'] == 'zh' or $GLOBALS['language'] == 'zh-CN') echo 'checked'; ?> class="custom-control-input change-language" type="radio" name="language" id="zh-CN" data-language="zh-CN">
                            <label class="custom-control-label" for="zh-CN">简体中文</label>
                        </div>
                    </li>
                    <li>
                        <div class="custom-control custom-radio">
                            <input <?php if ($GLOBALS['language'] == 'en') echo 'checked'; ?> class="custom-control-input change-language" type="radio" name="language" id="en" data-language="en">
                            <label class="custom-control-label" for="en">English</label>
                        </div>
                    </li>
                </ul>
            </section>
        <?php endif; ?>
        <?php if ($component == '自定义' && $this->options->customizeHTML): ?>
            <!--自定义HTML-->
            <section class="ml-xl-4 ml-lg-3 mb-5 customize">
                <h2 class="mb-4"><?php $this->options->customizeTitle(); ?></h2>
                <div class="customize-html"><?php $this->options->customizeHTML(); ?></div>
            </section>
        <?php endif; ?>
        <?php if ($component == '最新文章'): ?>
            <!--最新文章-->
            <section class="ml-xl-4 ml-lg-3 mb-5">
                <h2 class="mb-4"><?php echo $GLOBALS['t']['sidebar']['latestPosts']; ?></h2>
                <?php $latestArticles = $this->widget('Widget_Contents_Post_Recent'); ?>
                <?php $postSize = 0; ?>
                <?php if ($latestArticles->have()): ?>
                    <ul aria-label="<?php echo $GLOBALS['t']['sidebar']['latestPosts']; ?>">
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
                <?php else: ?>
                    <p class="pb-2 message"><?php echo $GLOBALS['t']['sidebar']['noPostsAvailableToDisplay']; ?></p>
                <?php endif; ?>    
            </section>
        <?php endif; ?>
        <?php if ($component == '最新回复'): ?>
            <!--最新回复-->
            <section class="ml-xl-4 ml-lg-3 latest-comment mb-5">
                <h2 class="mb-4"><?php echo $GLOBALS['t']['sidebar']['recentComments']; ?></h2>
                <ul aria-label="<?php echo $GLOBALS['t']['sidebar']['recentComments']; ?>" class="list-unstyled">
                    <?php $this->widget('Widget_Comments_Recent')->to($comments); ?>
                    <?php if ($comments->have()): ?>
                        <?php while($comments->next()): ?>
                            <li class="media mb-2">
                                <?php
                                    // 普通评论头像
                                    if ($comments->type == 'comment') {
                                        if ($this->options->QQAvatar == 'show' && isQQEmail($comments->mail)) {
                                            QQAvatar($comments->mail, $comments->author, 40);
                                        }else {
                                            gravatar($comments->mail, 50, $this->options->gravatarUrl, $comments->author);
                                        }
                                    }
                                    // 引用头像
                                    if ($comments->type == 'pingback') {
                                        echo '<div class="pingback avatar" role="img" aria-label="引用">引用</div>';
                                    }
                                ?>
                                <div class="media-body">
                                    <h5 class="mb-0 text-truncate">
                                        <a href="<?php $comments->permalink(); ?>" title="<?php printf($GLOBALS['t']['sidebar']['commentOn'], $comments->title); ?>" data-toggle="tooltip" data-placement="top">
                                            <?php $comments->author(false); ?>
                                        </a>
                                    </h5>
                                    <p class="m-0"><?php $comments->excerpt(40, '...'); ?></p>
                                </div>
                            </li>
                        <?php endwhile; ?>
                    <?php else: ?>    
                        <p class="pb-2 message"><?php echo $GLOBALS['t']['sidebar']['noCommentsOrRepliesAvailableToDisplay']; ?></p>
                    <?php endif; ?>    
                </ul>
            </section>
        <?php endif; ?>
        <?php if ($component == '文章分类'): ?>
            <!--分类-->
            <section class="ml-xl-4 ml-lg-3 mb-5 category">
                <h2 class="mb-4"><?php echo $GLOBALS['t']['sidebar']['categories']; ?></h2>
                <ul aria-label="<?php echo $GLOBALS['t']['sidebar']['categories']; ?>">
                    <?php $this->widget('Widget_Metas_Category_List')->to($category); ?>
                    <?php if ($category->have()): ?>
                        <?php while ($category->next()): ?>
                            <li <?php if ($category->parent > 0) echo 'class="ml-3"'; ?>>
                                <a rel="index" title="<?php if ($category->parent > 0) echo getParentCategory($category->parent) . ' 下的子分类 ' ?><?php $category->description(); ?>" href="<?php $category->permalink(); ?>" data-toggle="tooltip" data-placement="top">
                                    <?php $category->name(); ?>
                                    (<?php $category->count(); ?>)
                                </a>
                            </li>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <p class="pb-2 message"><?php echo $GLOBALS['t']['sidebar']['noCategoriesAvailableToDisplay']; ?></p>
                    <?php endif; ?>    
                </ul>
            </section>
        <?php endif; ?>
        <?php if ($component == '标签云'): ?>
            <!--标签云-->
            <section class="ml-xl-4 ml-lg-3 tags mb-5">
                <h2 class="mb-4"><?php echo $GLOBALS['t']['sidebar']['tags']; ?></h2>
                <?php $limit = $this->options->tagCount == 0?1000:$this->options->tagCount; ?>
                <?php $this->widget('Widget_Metas_Tag_Cloud', 'sort=mid&ignoreZeroCount=1&desc=0&limit=' . $limit)->to($tags); ?>
                <?php if($tags->have()): ?>
                    <div role="group" aria-label="<?php echo $GLOBALS['t']['sidebar']['tags']; ?>" class="clearfix">
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
                            <a role="listitem" title="<?php printf($GLOBALS['t']['sidebar']['tagPostCount'], $tags->count); ?>" href="<?php $tags->permalink(); ?>" rel="tag" class="p-1 float-left badge m-1 <?php echo $tagsColor[mt_rand(0, 5)]; ?>" data-toggle="tooltip" data-placement="top">
                                <?php $tags->name(); ?>(<?php $tags->count(); ?>)
                            </a>
                        <?php endwhile; ?>
                    </div>
                <?php else: ?>
                    <p class="pb-2 message"><?php echo $GLOBALS['t']['sidebar']['noTagsAvailableToDisplay']; ?></p>
                <?php endif; ?>
            </section>
        <?php endif; ?>
        <?php if ($component == '文章归档'): ?>
            <!--归档-->
            <section class="ml-xl-4 ml-lg-3 mb-5 archive">
                <h2 class="mb-4"><?php echo $GLOBALS['t']['sidebar']['archives']; ?></h2>
                <?php
                // 根据语言设置归档时间格式
                $format = $GLOBALS['language'] == 'en' ? 'F Y' : 'Y年m月';
                $postArchive = $this->widget('Widget_Contents_Post_Date', 'type=month&format=' . $format);
                ?>
                <?php if ($postArchive->have()): ?>
                    <ul aria-label="<?php echo $GLOBALS['t']['sidebar']['archives']; ?>" class="clearfix">
                        <?php while ($postArchive->next()): ?>
                            <li class="float-xl-left float-lg-none float-md-left float-sm-left float-left">
                                <a rel="archives" href="<?php $postArchive->permalink(); ?>" class="mr-2">
                                    <?php $postArchive->date(); ?>
                                    (<?php $postArchive->count(); ?>)
                                </a>
                            </li>
                        <?php endwhile; ?>
                    </ul>
                <?php else: ?>
                    <p class="pb-2 message"><?php echo $GLOBALS['t']['sidebar']['coPostsAvailableToGenerateAnArchive']; ?></p>
                <?php endif; ?>    
            </section>
        <?php endif; ?>
        <?php if ($component == '其它功能'): ?>
            <!--其它功能-->
            <section class="ml-xl-4 ml-lg-3 mb-5">
                <h2 class="mb-4"><?php echo $GLOBALS['t']['sidebar']['other']; ?></h2>
                <ul aria-label="<?php echo $GLOBALS['t']['sidebar']['other']; ?>">
                    <?php if ($this->options->loginLink == 'show'): ?>
                        <?php if($this->user->hasLogin()): ?>
                            <li>
                                <a href="<?php $this->options->adminUrl(); ?>"><?php printf($GLOBALS['t']['sidebar']['dashboard'], $this->user->screenName); ?></a>
                            </li>
                            <li>
                                <a href="<?php $this->options->logoutUrl(); ?>"><?php echo $GLOBALS['t']['sidebar']['logout']; ?></a>
                            </li>
                        <?php else: ?>
                            <li>
                                <a href="<?php $this->options->adminUrl('login.php'); ?>"><?php echo $GLOBALS['t']['sidebar']['login']; ?></a>
                            </li>
                        <?php endif; ?>
                    <?php endif; ?>
                    <li>
                        <a href="<?php $this->options->feedUrl(); ?>"><?php echo $GLOBALS['t']['sidebar']['RSSforPosts']; ?></a>
                    </li>
                    <li>
                        <a href="<?php $this->options->commentsFeedUrl(); ?>"><?php echo $GLOBALS['t']['sidebar']['RSSforComments']; ?></a>
                    </li>
                </ul>
            </section>
        <?php endif; ?>
        <?php if ($component == '友情链接'): ?>
            <!--友情链接-->
            <?php if ($this->options->links or $this->options->homeLinks && $this->is('index')): ?>
                <section class="ml-xl-4 ml-lg-3 mb-5">
                    <h2 class="mb-4"><?php echo $GLOBALS['t']['sidebar']['usefulLinks']; ?></h2>
                    <ul aria-label="<?php echo $GLOBALS['t']['sidebar']['usefulLinks']; ?>">
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
            <!--用于文章页的章节目录-->
            <section class="ml-xl-4 ml-lg-3 mb-5 directory d-none d-sm-none d-md-none d-lg-block d-xl-block">
                <h2 class="mb-4"><?php echo $GLOBALS['t']['sidebar']['tableOfContents']; ?></h2>
                <?php echo $GLOBALS['post']['directory']; ?>
            </section>
        <?php endif; ?>
    <?php endforeach; ?>
</aside>
