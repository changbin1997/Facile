<footer>
    <div class="container py-3">
        <nav class="text-center">
            Powered by
            <a class="mx-1" href="http://www.typecho.org/" target="_blank">Typecho</a>
            Theme by
            <span class="ml-1">Facile测试</span>
        </nav>
    </div>
</footer>

<script type="text/javascript" src="<?php $this->options->themeUrl('assets/js/jquery-3.5.1.min.js'); ?>"></script>
<script type="text/javascript" src="<?php $this->options->themeUrl('assets/js/bootstrap.bundle.min.js'); ?>"></script>
<script type="text/javascript" src="<?php $this->options->themeUrl('assets/js/highlight.pack.js'); ?>"></script>
<script type="text/javascript" src="<?php $this->options->themeUrl('assets/js/qrious.min.js'); ?>"></script>
<script type="text/javascript" src="<?php $this->options->themeUrl('assets/js/app.js'); ?>"></script>
<!--统计数据的图表js-->
<?php if (isset($GLOBALS['page']) && $GLOBALS['page'] == 'page-data'): ?>
    <script type="text/javascript" src="<?php $this->options->themeUrl('assets/js/tongji20210314.js'); ?>"></script>
<?php endif; ?>
<!--自定义HTML-->
<?php if ($this->options->bodyHTML): ?>
    <?php $this->options->bodyHTML(); ?>
<?php endif; ?>
<?php $this->footer(); ?>
</body>
</html>
