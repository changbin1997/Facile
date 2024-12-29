<?php if($this->allow('comment')): ?>

<div id="<?php $this->respondId(); ?>" class="comment-input">
    <h2><?php echo $GLOBALS['t']['comment']['leaveAComment']; ?></h2>
    <form method="post" action="<?php $this->commentUrl() ?>" id="comment-form" role="form">
        <div class="row">
            <div class="col-12">
                <label for="textarea" class="d-block">
                    <?php echo $GLOBALS['t']['comment']['commentContent']; ?>
                    <span class="required">*</span>
                </label>
                <textarea name="text" id="textarea" placeholder="<?php echo $GLOBALS['t']['comment']['enterYourCommentHere']; ?>" class="form-control" required></textarea>
            </div>
            <!--Emoji表情区域-->
            <?php if ($this->options->emojiPanel == 'show'): ?>
            <div class="col-12" id="emoji-box">
                <button aria-expanded="false" type="button" class="btn btn-sm" id="show-emoji-btn" data-url="<?php $this->options->themeUrl('emoji.php'); ?>">
                    😀 <?php echo $GLOBALS['t']['emoji']['emoji']; ?>
                </button>
                <div id="emoji-panel" class="bg-light border shadow rounded" role="dialog" aria-label="<?php echo $GLOBALS['t']['emoji']['emojiPanel']; ?>">
                    <div class="card card-body p-0 m-0 border-bottom">
                        <div id="emoji-classification" class="m-0 btn-group" role="group" aria-label="<?php echo $GLOBALS['t']['emoji']['emojiCategories']; ?>">
                            <button role="radio" aria-checked="true" aria-label="<?php echo $GLOBALS['t']['emoji']['smileys']; ?>" title="<?php echo $GLOBALS['t']['emoji']['smileys']; ?>" type="button" class="btn btn btn-sm selected" data-classification="smileys">😀</button>
                            <button role="radio" aria-checked="false" aria-label="<?php echo $GLOBALS['t']['emoji']['peopleAndGestures']; ?>" title="<?php echo $GLOBALS['t']['emoji']['peopleAndGestures']; ?>" type="button" class="btn btn btn-sm" data-classification="character">👦</button>
                            <button role="radio" aria-checked="false" aria-label="<?php echo $GLOBALS['t']['emoji']['clothingAndAccessories']; ?>" title="<?php echo $GLOBALS['t']['emoji']['clothingAndAccessories']; ?>" type="button" class="btn btn btn-sm" data-classification="clothing">👕</button>
                            <button role="radio" aria-checked="false" aria-label="<?php echo $GLOBALS['t']['emoji']['animalsAndNature']; ?>" title="<?php echo $GLOBALS['t']['emoji']['animalsAndNature']; ?>" type="button" class="btn btn btn-sm" data-classification="animal">🐶</button>
                            <button role="radio" aria-checked="false" aria-label="<?php echo $GLOBALS['t']['emoji']['food']; ?>" title="<?php echo $GLOBALS['t']['emoji']['food']; ?>" type="button" class="btn btn btn-sm" data-classification="food">🍏</button>
                            <button role="radio" aria-checked="false" aria-label="<?php echo $GLOBALS['t']['emoji']['activity']; ?>" title="<?php echo $GLOBALS['t']['emoji']['activity']; ?>" type="button" class="btn btn btn-sm" data-classification="motion">⚽</button>
                            <button role="radio" aria-checked="false" aria-label="<?php echo $GLOBALS['t']['emoji']['travelAndPlaces']; ?>" title="<?php echo $GLOBALS['t']['emoji']['travelAndPlaces']; ?>" type="button" class="btn btn-sm>" data-classification="tourism">🚚</button>
                            <button role="radio" aria-checked="false" aria-label="<?php echo $GLOBALS['t']['emoji']['objects']; ?>" title="<?php echo $GLOBALS['t']['emoji']['objects']; ?>" type="button" class="btn btn-sm>" data-classification="objects">⌚</button>
                            <button role="radio" aria-checked="false" aria-label="<?php echo $GLOBALS['t']['emoji']['symbols']; ?>" title="<?php echo $GLOBALS['t']['emoji']['symbols']; ?>" type="button" class="btn btn-sm>" data-classification="symbols">❤</button>
                        </div>
                    </div>
                    <h5 class="text-center py-2 m-0 border-bottom" id="emoji-title">表情类型</h5>
                    <div id="emoji-list" class="clearfix" role="list" aria-label="<?php echo $GLOBALS['t']['emoji']['emojiList'] . $GLOBALS['t']['emoji']['pressEnterToAddTheEmojiToTheCommentInputField']; ?>"></div>
                </div>
            </div>
            <?php endif; ?>
            <?php if($this->user->hasLogin()): ?>
                <div class="col-lg-12 comment-user">
                    <?php echo $GLOBALS['t']['comment']['loggedInAs']; ?>
                    <a href="<?php $this->options->profileUrl(); ?>" title="当前登录身份：<?php $this->user->screenName(); ?>">
                        <?php $this->user->screenName(); ?>
                    </a>.
                    <a href="<?php $this->options->logoutUrl(); ?>" title="<?php echo $GLOBALS['t']['sidebar']['logout']; ?>"><?php echo $GLOBALS['t']['sidebar']['logout']; ?> &raquo;</a>
                </div>
            <?php else: ?>
                <!--姓名输入-->
                <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
                    <label for="author" class="d-block">
                        <?php echo $GLOBALS['t']['comment']['name']; ?>
                        <span class="required">*</span>
                    </label>
                    <input type="text" class="form-control" placeholder="<?php echo $GLOBALS['t']['comment']['enterYourNameOrNickname']; ?>" name="author" id="author" value="<?php $this->remember('author'); ?>" required>
                </div>
                <!--邮箱地址输入-->
                <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
                    <label for="mail" class="d-block">
                        <?php echo $GLOBALS['t']['comment']['emailAddress']; ?>
                        <?php if ($this->options->commentsRequireMail): ?>
                            <span class="required">*</span>
                        <?php endif; ?>
                    </label>
                    <input type="email" class="form-control" placeholder="<?php echo $GLOBALS['t']['comment']['enterYourEmailAddress']; ?>" name="mail" id="mail" value="<?php $this->remember('mail'); ?>" <?php if ($this->options->commentsRequireMail) echo 'required'; ?>>
                </div>
                <!--网站地址输入-->
                <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
                    <label for="url" class="d-block">
                        <?php echo $GLOBALS['t']['comment']['website']; ?>
                        <?php if ($this->options->commentsRequireURL): ?>
                            <span class="required">*</span>
                        <?php endif; ?>
                    </label>
                    <input type="url" class="form-control" placeholder="<?php echo $GLOBALS['t']['comment']['enterYourWebsiteOrBlogURL']; ?>" name="url" id="url" value="<?php $this->remember('url'); ?>" <?php if ($this->options->commentsRequireURL) echo 'required'; ?>>
                </div>
            <?php endif; ?>
            <div class="col-12">
                <button type="submit" class="btn btn-primary"><?php echo $GLOBALS['t']['comment']['submitComment']; ?></button>
                <?php $comments->cancelReply(); ?>
            </div>
        </div>
    </form>
</div>

<?php else: ?>
    <div class="comment-off">
        <h2>评论功能已关闭</h2>
    </div>
<?php endif; ?>