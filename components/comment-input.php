<?php if($this->allow('comment')): ?>

<div id="<?php $this->respondId(); ?>" class="comment-input">
    <h2>发表评论</h2>
    <form method="post" action="<?php $this->commentUrl() ?>" id="comment-form" role="form">
        <div class="cancel-comment-reply">
            <?php $comments->cancelReply(); ?>
        </div>
        <div class="row">
            <div class="col-12">
                <label for="textarea" class="d-block">评论内容</label>
                <textarea name="text" id="textarea" placeholder="请在此处输入评论内容" class="form-control" required></textarea>
            </div>
            <?php if($this->user->hasLogin()): ?>
                <div class="col-lg-12 comment-user">
                    <?php _e('登录身份: '); ?>
                    <a href="<?php $this->options->profileUrl(); ?>" title="当前登录身份：<?php $this->user->screenName(); ?>">
                        <?php $this->user->screenName(); ?>
                    </a>.
                    <a href="<?php $this->options->logoutUrl(); ?>" title="退出"><?php _e('退出'); ?> &raquo;</a>
                </div>
            <?php else: ?>
                <!--姓名输入-->
                <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
                    <label for="author" class="d-block">姓名</label>
                    <input type="text" class="form-control" placeholder="请输入您的姓名或昵称" name="author" id="author" value="<?php $this->remember('author'); ?>" required>
                </div>
                <!--邮箱地址输入-->
                <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
                    <label for="mail" class="d-block">电子邮件地址（不会公开）</label>
                    <input type="email" class="form-control" placeholder="请输入您的电子邮件地址" name="mail" id="mail" value="<?php $this->remember('mail'); ?>" <?php if ($this->options->commentsRequireMail): ?> required <?php endif; ?>>
                </div>
                <!--网站地址输入-->
                <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
                    <label for="url" class="d-block">网站（选填）</label>
                    <input type="url" class="form-control" placeholder="请输入您的网站或博客地址" name="url" id="url" value="<?php $this->remember('url'); ?>" <?php if ($this->options->commentsRequireURL): ?> required <?php endif; ?>>
                </div>
            <?php endif; ?>
            <div class="col-12">
                <button type="submit" class="btn btn-primary btn-sm">提交评论</button>
            </div>
        </div>
    </form>
</div>

<?php else: ?>
    <div class="comment-off">
        <h2>评论功能已关闭</h2>
    </div>
<?php endif; ?>