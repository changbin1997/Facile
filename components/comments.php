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
                if ($comments->type == 'comment') {
                    if ($GLOBALS['QQAvatar'] == 'show' && isQQEmail($comments->mail)) {
                        QQAvatar($comments->mail, $comments->author, 40);
                    }else {
                        gravatar($comments->mail, 50, $GLOBALS['gravatarUrl'], $comments->author);
                    }
                }
                // 引用头像
                if ($comments->type == 'pingback') {
                    echo '<div class="pingback avatar" role="img" aria-label="' . $GLOBALS['t']['comment']['pingback'] . '">' . $GLOBALS['t']['comment']['pingbackAvatar'] . '</div>';
                }
                ?>
                <div class="comment-info float-left">
                    <b class="author"><?php $comments->author(); ?></b>
                    <!--作者评论-->
                    <?php if ($comments->authorId == $comments->ownerId): ?>
                        <span class="badge badge-dark author-tag"><?php echo $GLOBALS['t']['post']['author']; ?></span>
                    <?php endif; ?>
                    <?php echo reply($comments->parent); ?>
                    <!--评论审核中-->
                    <?php if ($comments->status != 'approved'): ?>
                        <span class="badge badge-dark" title="<?php echo $GLOBALS['t']['comment']['pendingReviewDescription']; ?>" data-toggle="tooltip" data-placement="top"><?php echo $GLOBALS['t']['comment']['pendingReview']; ?></span>
                    <?php endif; ?>
                    <!--私密评论-->
                    <?php $commentContent = parseSecretComment($comments->content, $comments); ?>
                    <?php if ($commentContent['hide']): ?>
                        <span class="badge badge-dark"><?php echo $GLOBALS['t']['comment']['secretComment']; ?></span>
                    <?php endif; ?>
                    <time class="comment-time" datetime="<?php echo date('c', $comments->created); ?>">
                        <?php echo commentDateFormat($comments->created, $GLOBALS['commentDateFormat']); ?>
                    </time>
                </div>
                <span class="comment-reply float-right" data-id="<?php $comments->theId(); ?>">
                    <?php $comments->reply('<i class="icon-undo2 mr-1"></i><span>' . $GLOBALS['t']['comment']['reply'] . '</span>'); ?>
                </span>
            </div>
            <div class="comment-content" id="c-<?php $comments->theId(); ?>">
                <?php if (!$commentContent['canView']): ?>
                    <div class="hide-comment-content p-1"><em><?php echo $GLOBALS['t']['comment']['secretCommentVisibility']; ?></em></div>
                <?php else: ?>
                    <?php echo $commentContent['content']; ?>
                <?php endif; ?>
            </div>
        </div>
        <?php if ($comments->children) { ?>
            <div class="comment-children clearfix">
                <?php $comments->threadedComments($options); ?>
            </div>
        <?php } ?>
    </li>
<?php } ?>

<div id="comments">
    <?php $this->comments()->to($comments); ?>
    <?php if ($this->options->commentInput == 'top') require_once 'comment-input.php'; ?>
    <?php if ($comments->have()): ?>
        <div class="comments-lists">
            <h2><?php $this->commentsNum($GLOBALS['t']['comment']['noComments'], $GLOBALS['t']['comment']['1Comment'], $GLOBALS['t']['comment']['thereAreNumComments']); ?></h2>

            <?php $comments->listComments(); ?>

            <nav class="page-nav my-5" aria-label="<?php echo $GLOBALS['t']['pagination']['commentPagination']; ?>">
                <?php bootstrap4Pagination($comments, $GLOBALS['t']['pagination']['previousPageNoShortcutKey'], $GLOBALS['t']['pagination']['nextPageNoShortcutKey']); ?>
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
<script type="text/javascript">
(function () {
    if (typeof window.TypechoComment === 'undefined' || typeof window.TypechoComment.reply !== 'function') {
        return;
    }
    if (window.TypechoComment.reply.__facileReposition) {
        return;
    }
    var originalReply = window.TypechoComment.reply;
    var respondId = '<?php echo $this->respondId; ?>';

    /* Typecho 1.3 会把回复表单插入到包含回复按钮的节点（comment-author）之后，
       即 comment-content 上方；这里在核心逻辑执行后把表单移动到 comment-content 下方，
       恢复 1.2 的插入位置，回复按钮仍保留在评论信息右侧。 */
    window.TypechoComment.reply = function (htmlId, coid, btn) {
        var result = originalReply.apply(this, arguments);
        var comment = document.getElementById(htmlId);
        var content = comment ? comment.querySelector('.comment-content') : null;
        if (comment && content) {
            var response = document.getElementById(respondId);
            if (response) {
                comment.insertBefore(response, content.nextSibling);
            }
        }
        return result;
    };
    window.TypechoComment.reply.__facileReposition = true;
})();
</script>
