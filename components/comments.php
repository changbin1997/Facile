<?php

$GLOBALS['commentDateFormat'] = $this->options->commentDateFormat;  // 获取日期格式设置
$GLOBALS['QQAvatar'] = $this->options->QQAvatar;  // 获取QQ头像设置
$GLOBALS['gravatarUrl'] = $this->options->gravatarUrl;  // 获取自定义 gravatar

function threadedComments($comments, $options) {
    $commentClass = '';
    if ($comments->authorId) {
        if ($comments->authorId == $comments->ownerId) {
            $commentClass .= ' comment-by-author';
        } else {
            $commentClass .= ' comment-by-user';
        }
    }

    $commentLevelClass = $comments->levels > 0 ? ' comment-child' : ' comment-parent';
    ?>

    <li id="li-<?php $comments->theId(); ?>" class="comment-body<?php
    if ($comments->levels > 0) {
        echo ' comment-child';
        $comments->levelsAlt(' comment-level-odd', ' comment-level-even');
    } else {
        echo ' comment-parent';
    }
    $comments->alt(' comment-odd', ' comment-even');
    echo $commentClass;
    ?>">
        <div id="<?php $comments->theId(); ?>" class="comment-box">
            <div class="comment-author clearfix">
                <?php
                // 普通评论头像
                if ($GLOBALS['QQAvatar'] == 'show' && isQQEmail($comments->mail)) {
                    QQAvatar($comments->mail, $comments->author, 40);
                }else {
                    gravatar($comments->mail, 50, $GLOBALS['gravatarUrl'], $comments->author);
                }
                // 引用头像
                if ($comments->type == 'pingback') {
                    echo '<div class="pingback avatar" role="img" aria-label="引用">引用</div>';
                }
                ?>
                <div class="comment-info float-left">
                    <b class="author"><?php $comments->author(); ?></b>
                    <?php if ($comments->authorId == $comments->ownerId): ?>
                        <span class="badge badge-dark">作者</span>
                    <?php endif; ?>
                    <?php echo reply($comments->parent); ?>
                    <?php if ($comments->status != 'approved'): ?>
                        <span class="badge badge-dark" title="您的评论目前只有您自己能看到，审核通过后才会公开显示。" data-toggle="tooltip" data-placement="top">评论审核中</span>
                    <?php endif; ?>
                    <span class="comment-time">
                        <?php echo dateFormat($comments->date->timeStamp, $GLOBALS['commentDateFormat']); ?>
                    </span>
                </div>
                <span class="comment-reply float-right" data-id="<?php $comments->theId(); ?>">
                    <?php $comments->reply('<i class="icon-undo2 mr-1"></i><span>回复</span>', 'active'); ?>
                </span>
            </div>
            <div class="comment-content" id="c-<?php $comments->theId(); ?>">
                <?php $comments->content(); ?>
            </div>
        </div>
        <?php if ($comments->children) { ?>
            <div class="comment-children clearfix">
                <?php $comments->threadedComments($options); ?>
            </div>
        <?php } ?>
    </li>
<?php } ?>

<div id="comments" aria-label="评论区">
    <?php $this->comments()->to($comments); ?>
    <?php if ($this->options->commentInput == 'top') require_once 'comment-input.php'; ?>
    <?php if ($comments->have()): ?>
        <div class="comments-lists">
            <h2><?php $this->commentsNum(_t('暂无评论'), _t('仅有一条评论'), _t('已有 %d 条评论')); ?></h2>

            <?php $comments->listComments(); ?>

            <nav class="page-nav my-5" aria-label="分页导航">
                <?php $comments->pageNav('<i class="icon-chevron-left"></i>', '<i class="icon-chevron-right"></i>', 1, '...', array('wrapTag' => 'ul', 'wrapClass' => 'pagination justify-content-center', 'itemTag' => 'li', 'textTag' => 'a', 'currentClass' => 'active', 'prevClass' => 'prev', 'nextClass' => 'next')); ?>
            </nav>

        </div>
    <?php endif; ?>
    <?php if ($this->options->commentInput == 'bottom' or $this->options->commentInput == null) require_once 'comment-input.php'; ?>
</div>

<?php if ($this->options->pjax == 'on'): ?>
<script type="text/javascript">
  (function() {
    window.TypechoComment = {
      dom: function(id) {
        return document.getElementById(id);
      },
      create: function(tag, attr) {
        var el = document.createElement(tag);
        for (var key in attr) {
          el.setAttribute(key, attr[key]);
        }
        return el;
      },
      reply: function(cid, coid) {
        var comment = this.dom(cid),
            parent = comment.parentNode,
            response = this.dom('<?php echo $this->respondId; ?>'),
            input = this.dom('comment-parent'),
            form = 'form' == response.tagName ? response : response.getElementsByTagName('form')[0],
            textarea = response.getElementsByTagName('textarea')[0];
        if (null == input) {
          input = this.create('input', {
            'type': 'hidden',
            'name': 'parent',
            'id': 'comment-parent'
          });
          form.appendChild(input);
        }
        input.setAttribute('value', coid);
        if (null == this.dom('comment-form-place-holder')) {
          var holder = this.create('div', {
            'id': 'comment-form-place-holder'
          });
          response.parentNode.insertBefore(holder, response);
        }
        comment.appendChild(response);
        this.dom('cancel-comment-reply-link').style.display = '';
        if (null != textarea && 'text' == textarea.name) {
          textarea.focus();
        }
        return false;
      },
      cancelReply: function() {
        var response = this.dom('<?php echo $this->respondId; ?>'),
            holder = this.dom('comment-form-place-holder'),
            input = this.dom('comment-parent');
        if (null != input) {
          input.parentNode.removeChild(input);
        }
        if (null == holder) {
          return true;
        }
        this.dom('cancel-comment-reply-link').style.display = 'none';
        holder.parentNode.insertBefore(response, holder);
        return false;
      }
    };
  })();
</script>
<?php endif; ?>