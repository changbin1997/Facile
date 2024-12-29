<footer>
    <div class="container py-3">
        <?php if ($this->options->icp): ?>
            <nav class="text-center mb-1"><?php $this->options->icp(); ?></nav>
        <?php endif; ?>
        <nav class="text-center">
            Powered by
            <a class="mx-1" href="http://www.typecho.org/" target="_blank">Typecho</a>
            Theme by
            <a class="ml-1" href="https://github.com/changbin1997/Facile" target="_blank">Facile</a>
        </nav>
    </div>
</footer>

<button class="btn text-primary rounded-circle d-none" id="to-top-btn" type="button" aria-label="<?php echo $GLOBALS['t']['scrollToTop']; ?>" title="<?php echo $GLOBALS['t']['scrollToTop']; ?>">
    <i class="icon-arrow-up"></i>
</button>

<script type="text/javascript" src="<?php $this->options->themeUrl('assets/js/jquery-3.5.1.min.js'); ?>"></script>
<script type="text/javascript" src="<?php $this->options->themeUrl('assets/js/jquery.pjax.js'); ?>"></script>
<script type="text/javascript" src="<?php $this->options->themeUrl('assets/js/bootstrap.bundle.min.js'); ?>"></script>
<script type="text/javascript" src="<?php $this->options->themeUrl('assets/js/highlight.pack.js'); ?>"></script>
<script type="text/javascript" src="<?php $this->options->themeUrl('assets/js/qrious.min.js'); ?>"></script>
<script type="text/javascript" src="<?php $this->options->themeUrl('assets/js/clipboard.min.js'); ?>"></script>
<script type="text/javascript" src="<?php $this->options->themeUrl('assets/js/app.js'); ?>"></script>
<!--PJAX更新完成后执行的JS-->
<?php if ($this->options->pjax === 'on' && $this->options->pjaxEnd): ?>
    <script type="text/javascript">
        $(function() {
          $(document).on('pjax:end', function() {<?php $this->options->pjaxEnd(); ?>});
        });
    </script>
<?php endif; ?>
<!--自定义HTML-->
<?php if ($this->options->bodyHTML): ?>
    <?php $this->options->bodyHTML(); ?>
<?php endif; ?>
<?php $this->footer(); ?>
</body>
</html>
