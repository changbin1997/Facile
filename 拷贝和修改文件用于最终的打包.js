#!/usr/bin/env node
'use strict';

/**
 * Facile 主题发布打包脚本
 *
 * 把构建产物（dist）和主题源码打包成一个可发布的 Facile 目录。
 *
 * 步骤：
 *  1. 检查 dist 目录是否存在，不存在则停止
 *  2. 生成 Facile 目录结构
 *  3. 拷贝根目录 .php 文件、components、inc、languages、LICENSE、README.md、screenshot.jpg
 *  4. 拷贝 assets 中需要保留的 css/js 文件（不读取大文件内容）
 *  5. 从 dist 拷贝 style-*.css 和 bundle-*.js 到 Facile/assets
 *  6. 修改 header.php / footer.php 引用打包后的 css/js
 *  7. 询问是否添加网站信息，询问版本号
 *
 * 任一步出错立即停止执行。
 */

const fs = require('fs');
const path = require('path');
const readline = require('readline');

const ROOT = __dirname;
const DIST = path.join(ROOT, 'dist');
const FACILE = path.join(ROOT, 'Facile');
const ASSETS = path.join(ROOT, 'assets');

// 需要从 assets 拷贝的固定 css/js 文件（只拷贝，不读取内容）
const CSS_FILES = ['options-panel.css'];
const JS_FILES = ['ECharts.js', 'highlight.pack.js', 'options-panel.js', 'sw.js'];

const rl = readline.createInterface({ input: process.stdin, output: process.stdout });

// 行队列方式处理输入，兼容交互式输入和管道一次性输入
const pendingQuestions = [];
const inputQueue = [];

rl.on('line', function (line) {
  if (pendingQuestions.length > 0) {
    const resolve = pendingQuestions.shift();
    resolve(line);
  } else {
    inputQueue.push(line);
  }
});

rl.on('close', function () {
  if (pendingQuestions.length > 0) {
    log('[失败] 输入提前结束，无法继续询问');
    process.exit(1);
  }
});

// 实时输出信息
function log(message) {
  process.stdout.write(message + '\n');
}

// 出错时输出信息并立即停止执行
function fail(message) {
  log('[失败] ' + message);
  process.exit(1);
}

function step(message) {
  log('\n[步骤] ' + message);
}

function readUtf8(file) {
  return fs.readFileSync(file, 'utf8');
}

function writeUtf8(file, content) {
  fs.writeFileSync(file, content, 'utf8');
}

// 检测文件使用的换行符，修改时保持与原文件一致
function detectEol(content) {
  const crlf = (content.match(/\r\n/g) || []).length;
  const lf = (content.match(/\n/g) || []).length;
  return crlf > lf / 2 ? '\r\n' : '\n';
}

// 询问输入
function ask(question) {
  return new Promise(function (resolve) {
    process.stdout.write(question);
    if (inputQueue.length > 0) {
      process.nextTick(function () {
        resolve(inputQueue.shift());
      });
    } else {
      pendingQuestions.push(resolve);
    }
  });
}

// 拷贝单个文件并校验
function copyOne(src, dest) {
  if (!fs.existsSync(src)) {
    fail('源文件不存在：' + src);
  }
  fs.copyFileSync(src, dest);
  if (!fs.existsSync(dest)) {
    fail('拷贝失败，目标文件未生成：' + dest);
  }
  log('  已拷贝：' + path.relative(ROOT, dest));
}

// 递归拷贝目录下的所有文件
function copyDir(srcDir, destDir) {
  if (!fs.existsSync(srcDir)) {
    fail('目录不存在：' + srcDir);
  }
  const entries = fs.readdirSync(srcDir);
  if (entries.length === 0) {
    fail('目录为空：' + srcDir);
  }
  fs.mkdirSync(destDir, { recursive: true });
  for (const entry of entries) {
    const srcEntry = path.join(srcDir, entry);
    const destEntry = path.join(destDir, entry);
    const stat = fs.statSync(srcEntry);
    if (stat.isDirectory()) {
      copyDir(srcEntry, destEntry);
    } else {
      copyOne(srcEntry, destEntry);
    }
  }
}

// 修改 header.php 的 CSS 引用
function replaceHeader(file, styleCss) {
  let content = readUtf8(file);
  const eol = detectEol(content);
  const oldBlock = [
    '    <link rel="stylesheet" href="<?php $this->options->themeUrl(\'assets/css/bootstrap.css\'); ?>" type="text/css">',
    '    <link rel="stylesheet" href="<?php $this->options->themeUrl(\'assets/css/style.css\'); ?>" type="text/css">',
    '    <link rel="stylesheet" href="<?php $this->options->themeUrl(\'assets/css/icon.css\'); ?>" type="text/css">'
  ].join(eol);
  const newBlock = '    <link rel="stylesheet" href="<?php $this->options->themeUrl(\'assets/css/' + styleCss + '\'); ?>" type="text/css">';
  if (content.indexOf(oldBlock) === -1) {
    fail('header.php 中没有找到需要替换的 CSS 引用，未按预期执行');
  }
  content = content.split(oldBlock).join(newBlock);
  if (content.indexOf(newBlock) === -1) {
    fail('header.php CSS 引用替换失败');
  }
  writeUtf8(file, content);
  log('  已把 CSS 引用替换为：assets/css/' + styleCss);
}

// 修改 footer.php 的 JS 引用
function replaceFooter(file, bundleJs) {
  let content = readUtf8(file);
  const eol = detectEol(content);
  const oldBlock = [
    '<script type="text/javascript" src="<?php $this->options->themeUrl(\'assets/js/jquery-3.5.1.min.js\'); ?>"></script>',
    '<script type="text/javascript" src="<?php $this->options->themeUrl(\'assets/js/jquery.pjax.js\'); ?>"></script>',
    '<script type="text/javascript" src="<?php $this->options->themeUrl(\'assets/js/bootstrap.bundle.min.js\'); ?>"></script>',
    '<script type="text/javascript" src="<?php $this->options->themeUrl(\'assets/js/qrious.min.js\'); ?>"></script>',
    '<script type="text/javascript" src="<?php $this->options->themeUrl(\'assets/js/clipboard.min.js\'); ?>"></script>',
    '<script type="module" src="<?php $this->options->themeUrl(\'assets/js/app.js\'); ?>"></script>'
  ].join(eol);
  const newBlock = '<script type="text/javascript" src="<?php $this->options->themeUrl(\'assets/js/' + bundleJs + '\'); ?>"></script>';
  if (content.indexOf(oldBlock) === -1) {
    fail('footer.php 中没有找到需要替换的 JS 引用，未按预期执行');
  }
  content = content.split(oldBlock).join(newBlock);
  if (content.indexOf(newBlock) === -1) {
    fail('footer.php JS 引用替换失败');
  }
  writeUtf8(file, content);
  log('  已把 JS 引用替换为：assets/js/' + bundleJs);
}

// 在 footer.php 添加网站信息
function addSiteInfo(file) {
  let content = readUtf8(file);
  const eol = detectEol(content);
  const navBlock = [
    '        <nav class="text-center">',
    '            Powered by',
    '            <a class="mx-1" href="http://www.typecho.org/" target="_blank">Typecho</a>',
    '            Theme by',
    '            <a class="ml-1" href="https://github.com/changbin1997/Facile" target="_blank">Facile</a>',
    '        </nav>'
  ].join(eol);
  const infoBlock = [
    '        <nav class="text-center">',
    '            © <?php echo date(\'Y\', time()); ?> www.misterma.com',
    '            <a href="https://www.misterma.com/sitemap.xml" class="ml-1">sitemap</a>',
    '        </nav>'
  ].join(eol);
  if (content.indexOf(navBlock) === -1) {
    fail('footer.php 中没有找到 Powered by 导航区域，未按预期执行');
  }
  if (content.indexOf(infoBlock) !== -1) {
    log('  网站信息已存在，跳过添加');
    return;
  }
  content = content.split(navBlock).join(infoBlock + eol + navBlock);
  if (content.indexOf(infoBlock) === -1) {
    fail('footer.php 添加网站信息失败');
  }
  writeUtf8(file, content);
  log('  已在 footer.php 中添加网站信息');
}

// 修改 index.php 版本号
function replaceVersion(file, version) {
  let content = readUtf8(file);
  const oldLine = ' * @version 开发板（暂无版本号）';
  const newLine = ' * @version ' + version;
  if (content.indexOf(oldLine) === -1) {
    fail('index.php 中没有找到 @version 开发板（暂无版本号），未按预期执行');
  }
  content = content.split(oldLine).join(newLine);
  if (content.indexOf(newLine) === -1) {
    fail('index.php 版本号替换失败');
  }
  writeUtf8(file, content);
  log('  已把 index.php 版本号设置为：' + version);
}

// 修改 theme-config.php 的提示文字
function replaceThemeConfig(file, version) {
  let content = readUtf8(file);
  const oldText = '您现在使用的是 Facile 的开发板，开发板暂无版本号。';
  const newText = '您现在使用的是 Facile ' + version;
  if (content.indexOf(oldText) === -1) {
    fail('theme-config.php 中没有找到开发板提示文字，未按预期执行');
  }
  content = content.split(oldText).join(newText);
  if (content.indexOf(newText) === -1) {
    fail('theme-config.php 提示文字替换失败');
  }
  writeUtf8(file, content);
  log('  已把 theme-config.php 的提示文字设置为：Facile ' + version);
}

async function main() {
  // ---- 1. 检查 dist ----
  step('检查 dist 目录');
  if (!fs.existsSync(DIST) || !fs.statSync(DIST).isDirectory()) {
    fail('未找到 dist 目录，无法打包，已停止执行');
  }
  const distCss = fs.readdirSync(DIST).filter(function (name) {
    return /^style-.+\.css$/.test(name);
  });
  const distJs = fs.readdirSync(DIST).filter(function (name) {
    return /^bundle-.+\.js$/.test(name);
  });
  if (distCss.length !== 1) {
    fail('dist 中的 style-*.css 数量不是 1（实际 ' + distCss.length + ' 个）');
  }
  if (distJs.length !== 1) {
    fail('dist 中的 bundle-*.js 数量不是 1（实际 ' + distJs.length + ' 个）');
  }
  const styleCss = distCss[0];
  const bundleJs = distJs[0];
  log('  找到：' + styleCss + '、' + bundleJs);

  // ---- 2. 创建 Facile 目录 ----
  step('创建 Facile 目录');
  if (fs.existsSync(FACILE)) {
    fs.rmSync(FACILE, { recursive: true, force: true });
    log('  检测到已存在的 Facile 目录，已删除');
  }
  fs.mkdirSync(FACILE, { recursive: true });
  if (!fs.existsSync(FACILE)) {
    fail('Facile 目录创建失败');
  }
  log('  已创建：' + path.relative(ROOT, FACILE));

  // ---- 3. 拷贝根目录的 .php 文件 ----
  step('拷贝根目录的 .php 文件');
  const phpFiles = fs.readdirSync(ROOT).filter(function (name) {
    return /\.php$/i.test(name) && fs.statSync(path.join(ROOT, name)).isFile();
  });
  if (phpFiles.length === 0) {
    fail('根目录没有 .php 文件');
  }
  for (const name of phpFiles) {
    copyOne(path.join(ROOT, name), path.join(FACILE, name));
  }

  // ---- 4. components ----
  step('拷贝 components 目录');
  copyDir(path.join(ROOT, 'components'), path.join(FACILE, 'components'));

  // ---- 5. inc ----
  step('拷贝 inc 目录');
  copyDir(path.join(ROOT, 'inc'), path.join(FACILE, 'inc'));

  // ---- 6. languages ----
  step('拷贝 languages 目录');
  copyDir(path.join(ROOT, 'languages'), path.join(FACILE, 'languages'));

  // ---- 7. LICENSE / README / screenshot ----
  step('拷贝 LICENSE、README.md、screenshot.jpg');
  const extraFiles = ['LICENSE', 'README.md', 'screenshot.jpg'];
  for (const name of extraFiles) {
    copyOne(path.join(ROOT, name), path.join(FACILE, name));
  }

  // ---- 8. 创建 assets 目录 ----
  step('创建 Facile/assets 目录');
  fs.mkdirSync(path.join(FACILE, 'assets', 'css'), { recursive: true });
  fs.mkdirSync(path.join(FACILE, 'assets', 'js'), { recursive: true });
  log('  已创建：assets/css、assets/js');

  // ---- 9. 拷贝固定的 css/js ----
  step('拷贝需要保留的 assets/css 和 assets/js 文件');
  for (const name of CSS_FILES) {
    copyOne(path.join(ASSETS, 'css', name), path.join(FACILE, 'assets', 'css', name));
  }
  for (const name of JS_FILES) {
    copyOne(path.join(ASSETS, 'js', name), path.join(FACILE, 'assets', 'js', name));
  }

  // ---- 10. 从 dist 拷贝打包产物 ----
  step('从 dist 拷贝打包后的 css 和 js');
  copyOne(path.join(DIST, styleCss), path.join(FACILE, 'assets', 'css', styleCss));
  copyOne(path.join(DIST, bundleJs), path.join(FACILE, 'assets', 'js', bundleJs));

  // ---- 11. 修改 header.php ----
  step('修改 Facile/components/header.php 的 CSS 引用');
  replaceHeader(path.join(FACILE, 'components', 'header.php'), styleCss);

  // ---- 12. 修改 footer.php 的 JS 引用 ----
  step('修改 Facile/components/footer.php 的 JS 引用');
  replaceFooter(path.join(FACILE, 'components', 'footer.php'), bundleJs);

  // ---- 13. 询问是否添加网站信息 ----
  step('询问是否添加网站信息');
  const infoAnswer = (await ask('是否添加网站信息？(y/n，默认 y): ')).trim().toLowerCase();
  if (infoAnswer === '' || infoAnswer === 'y' || infoAnswer === 'yes') {
    addSiteInfo(path.join(FACILE, 'components', 'footer.php'));
  } else if (infoAnswer === 'n' || infoAnswer === 'no') {
    log('  已选择不添加网站信息');
  } else {
    fail('无效输入：' + infoAnswer + '（请输入 y 或 n）');
  }

  // ---- 14. 版本号 ----
  step('输入版本号');
  const version = (await ask('请输入版本号（留空跳过）: ')).trim();
  if (version === '') {
    log('  未输入版本号，跳过版本号设置');
  } else {
    if (!/^[\p{L}\p{N}_.\-]+$/u.test(version)) {
      fail('版本号格式不正确：' + version);
    }
    replaceVersion(path.join(FACILE, 'index.php'), version);
    replaceThemeConfig(path.join(FACILE, 'inc', 'theme-config.php'), version);
  }

  log('');
  log('[完成] 打包成功：' + path.relative(ROOT, FACILE));
}

main().then(function () {
  rl.close();
}).catch(function (err) {
  const message = err && err.message ? err.message : String(err);
  fail('发生未预期错误：' + message);
});
