#!/usr/bin/env node
'use strict';

/**
 * Facile 主题前端资源处理脚本
 *
 * 功能：
 *  1. 在项目根目录生成 src 目录结构（src/js、src/style、src/style/fonts）
 *  2. 把 assets/css 中指定的 CSS / SCSS 文件拷贝到 src/style
 *  3. 把 assets/fonts 中的所有字体文件拷贝到 src/style/fonts
 *  4. 改写 src/style/icon.css 中的字体引用路径（../fonts/ -> ./fonts/）
 *  5. 生成 src/js/app.js：
 *     - 版权注释和所有 import 放在文件顶部
 *     - 把 assets/js/app.js 中 $(function() {...}) 回调内的代码放入 export default 函数
 *  6. 把 assets/js/modules 中的 JS 模块拷贝到 src/js/modules
 *     - 在 ArticleEngagement.js 顶部添加 import QRious from 'qrious';
 *     - 在 CodeAndMath.js 顶部添加 import ClipboardJS from 'clipboard';
 *
 * 任意一步出错会立即停止执行。
 */

const fs = require('fs');
const path = require('path');

const ROOT = __dirname;
const ASSETS = path.join(ROOT, 'assets');
const SRC = path.join(ROOT, 'src');
const SRC_JS = path.join(SRC, 'js');
const SRC_STYLE = path.join(SRC, 'style');
const SRC_FONTS = path.join(SRC_STYLE, 'fonts');

const CSS_FILES = [
  'bootstrap.css',
  'dark-color.scss',
  'icon.css',
  'light-color.scss',
  'stackoverflow-light.min.css',
  'style.scss',
  'sunburst.min.css',
  'vs2015.min.css'
];

// 需要在模块文件顶部添加的 import
const MODULE_IMPORTS = {
  'ArticleEngagement.js': "import QRious from 'qrious';",
  'CodeAndMath.js': "import ClipboardJS from 'clipboard';",
  'PJAX.js': "import './../jquery.pjax.js';"
};

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

// 检测文件使用的换行符，写入时保持与原文件一致
function detectEol(content) {
  const crlf = (content.match(/\r\n/g) || []).length;
  const lf = (content.match(/\n/g) || []).length;
  return crlf > lf / 2 ? '\r\n' : '\n';
}

// 在文件顶部的版权注释之后插入 import 语句
function prependImport(file, importLine) {
  const content = readUtf8(file);
  const eol = detectEol(content);
  const commentMatch = content.match(/^\s*\/\*!?[\s\S]*?\*\/\s*/);
  let newContent;
  if (commentMatch) {
    newContent = commentMatch[0].replace(/\s+$/, '') + eol + eol + importLine + eol + content.slice(commentMatch[0].length);
  } else {
    newContent = importLine + eol + content;
  }
  writeUtf8(file, newContent);
}

function main() {
  // ---- 1. 创建目录 ----
  step('创建 src 目录结构');
  const dirs = [SRC, SRC_JS, SRC_STYLE, SRC_FONTS];
  for (const dir of dirs) {
    fs.mkdirSync(dir, { recursive: true });
    if (!fs.existsSync(dir)) {
      fail('目录创建失败：' + dir);
    }
    log('  已创建（或已存在）：' + path.relative(ROOT, dir));
  }

  // ---- 2. 拷贝 CSS / SCSS ----
  step('拷贝 CSS / SCSS 文件到 src/style');
  const cssSrcDir = path.join(ASSETS, 'css');
  if (!fs.existsSync(cssSrcDir)) {
    fail('CSS 目录不存在：' + cssSrcDir);
  }
  for (const name of CSS_FILES) {
    const srcFile = path.join(cssSrcDir, name);
    const destFile = path.join(SRC_STYLE, name);
    if (!fs.existsSync(srcFile)) {
      fail('源文件不存在：' + srcFile);
    }
    fs.copyFileSync(srcFile, destFile);
    if (!fs.existsSync(destFile)) {
      fail('拷贝失败，目标文件未生成：' + destFile);
    }
    log('  已拷贝：' + name);
  }

  // ---- 3. 拷贝字体 ----
  step('拷贝字体文件到 src/style/fonts');
  const fontsSrcDir = path.join(ASSETS, 'fonts');
  if (!fs.existsSync(fontsSrcDir)) {
    fail('字体目录不存在：' + fontsSrcDir);
  }
  const fontFiles = fs.readdirSync(fontsSrcDir).filter(function (name) {
    return fs.statSync(path.join(fontsSrcDir, name)).isFile();
  });
  if (fontFiles.length === 0) {
    fail('字体目录中没有文件：' + fontsSrcDir);
  }
  for (const name of fontFiles) {
    const srcFile = path.join(fontsSrcDir, name);
    const destFile = path.join(SRC_FONTS, name);
    fs.copyFileSync(srcFile, destFile);
    if (!fs.existsSync(destFile)) {
      fail('拷贝失败，目标文件未生成：' + destFile);
    }
    log('  已拷贝：' + name);
  }

  // ---- 4. 改写 icon.css ----
  step('改写 src/style/icon.css 中的字体路径');
  const iconCssFile = path.join(SRC_STYLE, 'icon.css');
  const iconCss = readUtf8(iconCssFile);
  if (iconCss.indexOf('../fonts/') === -1) {
    fail('icon.css 中没有找到 ../fonts/ 引用，未按预期处理');
  }
  const newIconCss = iconCss.split('../fonts/').join('./fonts/');
  if (newIconCss.indexOf('../fonts/') !== -1) {
    fail('icon.css 字体路径改写失败');
  }
  writeUtf8(iconCssFile, newIconCss);
  log('  已把 ../fonts/ 全部改写为 ./fonts/');

  // ---- 5. 生成 src/js/app.js ----
  step('生成 src/js/app.js');
  const sourceAppFile = path.join(ASSETS, 'js', 'app.js');
  if (!fs.existsSync(sourceAppFile)) {
    fail('源文件不存在：' + sourceAppFile);
  }
  const sourceApp = readUtf8(sourceAppFile);
  const eol = detectEol(sourceApp);
  const marker = sourceApp.indexOf('$(function');
  if (marker === -1) {
    fail('assets/js/app.js 中没有找到 $(function 回调');
  }
  const openBrace = sourceApp.indexOf('{', marker);
  if (openBrace === -1) {
    fail('assets/js/app.js 中没有找到 $(function 回调的函数体');
  }
  let depth = 0;
  let closeBrace = -1;
  for (let i = openBrace; i < sourceApp.length; i++) {
    if (sourceApp.charAt(i) === '{') {
      depth++;
    } else if (sourceApp.charAt(i) === '}') {
      depth--;
      if (depth === 0) {
        closeBrace = i;
        break;
      }
    }
  }
  if (closeBrace === -1) {
    fail('assets/js/app.js 的 $(function 回调函数体匹配失败');
  }
  const header = sourceApp.slice(0, marker);
  const body = sourceApp.slice(openBrace + 1, closeBrace);
  const bodyStart = body.replace(/^\n+/, '');
  const newApp = header.replace(/\s+$/, '') + eol + eol + 'export default () => {' + eol + bodyStart.replace(/\s+$/, '') + eol + '}' + eol;
  const destAppFile = path.join(SRC_JS, 'app.js');
  writeUtf8(destAppFile, newApp);

  // 校验生成结果
  const checkApp = readUtf8(destAppFile);
  if (checkApp.indexOf('export default () => {') === -1) {
    fail('src/js/app.js 中没有找到 export default，生成结果不符合预期');
  }
  if (checkApp.indexOf('HomePage: https://www.misterma.com') === -1) {
    fail('src/js/app.js 中没有找到版权注释，生成结果不符合预期');
  }
  const importCount = (checkApp.match(/^import\s/gm) || []).length;
  if (importCount !== 12) {
    fail('src/js/app.js 中的 import 数量不是 12（实际 ' + importCount + '），生成结果不符合预期');
  }
  if (checkApp.indexOf('$(function') !== -1) {
    fail('src/js/app.js 中仍然包含 $(function 包装，生成结果不符合预期');
  }
  log('  已生成：src/js/app.js（' + importCount + ' 个 import）');

  // ---- 6. 拷贝 JS 模块 ----
  step('拷贝 JS 模块文件到 src/js/modules');
  const modulesSrcDir = path.join(ASSETS, 'js', 'modules');
  const modulesDestDir = path.join(SRC_JS, 'modules');
  if (!fs.existsSync(modulesSrcDir)) {
    fail('模块目录不存在：' + modulesSrcDir);
  }
  fs.mkdirSync(modulesDestDir, { recursive: true });
  const moduleFiles = fs.readdirSync(modulesSrcDir).filter(function (name) {
    return name.endsWith('.js') && fs.statSync(path.join(modulesSrcDir, name)).isFile();
  });
  if (moduleFiles.length === 0) {
    fail('模块目录中没有 JS 文件：' + modulesSrcDir);
  }
  for (const name of moduleFiles) {
    const srcFile = path.join(modulesSrcDir, name);
    const destFile = path.join(modulesDestDir, name);
    fs.copyFileSync(srcFile, destFile);
    if (!fs.existsSync(destFile)) {
      fail('拷贝失败，目标文件未生成：' + destFile);
    }
    log('  已拷贝：' + name);
  }

  // ---- 7. 修改模块文件，添加 import ----
  step('在模块文件顶部添加 import');
  const importNames = Object.keys(MODULE_IMPORTS);
  for (const name of importNames) {
    const target = path.join(modulesDestDir, name);
    if (!fs.existsSync(target)) {
      fail('模块文件不存在：' + target);
    }
    const importLine = MODULE_IMPORTS[name];
    prependImport(target, importLine);
    const content = readUtf8(target);
    if (content.split(importLine).length !== 2) {
      fail('import 写入失败或重复：' + target);
    }
    log('  已添加 ' + importLine + ' 到 ' + name);
  }

  log('');
  log('[完成] 全部处理成功。');
}

try {
  main();
} catch (err) {
  const message = err && err.message ? err.message : String(err);
  fail('发生未预期错误：' + message);
}
