<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */
namespace xutl\ueditor;

use Yii;
use yii\web\AssetBundle;

/**
 * Class MarkdownEditorAsset
 * @package xutl\ueditor
 */
class UEditorAsset extends AssetBundle
{
    public $sourcePath = '@xutl/ueditor/assets';

    public $js = [
        'ueditor.config.js',
        'ueditor.all.min.js',
    ];

    public $css = [
        'themes/iframe.css',
    ];

    public $depends = [
        'yii\web\JqueryAsset',
    ];
}