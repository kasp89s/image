<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\assets;

use yii\web\AssetBundle;

/**
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        '/web/css/style.css',
        '/web/css/poop.css',
        '/web/colorbox/colorbox.css',
        '/web/css/jquery.jscrollpane.css',
        '/web/css/jcarousel.connected-carousels.css',
        '/web/css/cropper.min.css',
    ];
    public $js = [
		'/web/colorbox/jquery.colorbox-min.js',
		'/web/js/jquery.mousewheel.js',
		'/web/js/jquery.jscrollpane.min.js',
		'/web/js/main.js',
		'/web/js/afterUpload.js',
		'/web/js/profile.js',
		'/web/js/comments.js',
		'/web/js/jquery.selectric.js',
		'/web/js/uisearch.js',
		'/web/js/jquery.plugin.min.js',
		'/web/js/jquery.maxlength.min.js',
		'/web/js/jquery-ui.js',
//		'/web/galleria/galleria-1.4.2.min.js',
//		'/web/galleria/galleria.history.min.js',
//		'/web/galleria/galleria.classic.min.js',
		'/web/js/jquery.jcarousel.min.js',
		'/web/js/sly.min.js',
		'/web/js/clipboard.min.js',
		'/web/js/cropper.min.js',
//		'/web/js/imageEditor.js',
//		'/web/js/init.script.js',
   ];
    public $depends = [
        'yii\web\YiiAsset',
        //'yii\bootstrap\BootstrapAsset',
    ];
}

