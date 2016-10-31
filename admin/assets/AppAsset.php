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
        '/admin/web/css/bootstrap.min.css',
        '/admin/web/css/metisMenu.min.css',
        '/admin/web/css/bootstrap-datepicker.min.css',
        '/admin/web/css/dataTables.bootstrap.css',
        '/admin/web/css/sb-admin-2.css',
        '/admin/web/css/font-awesome.min.css',
    ];
    public $js = [
		'/admin/web/js/jquery.min.js',
		'/admin/web/js/jquery.ui.js',
		'/admin/web/js/bootstrap.min.js',
		'/admin/web/js/metisMenu.min.js',
		'/admin/web/js/bootstrap-datepicker.min.js',
		'/admin/web/js/jquery.dataTables.min.js',
		'/admin/web/js/dataTables.bootstrap.min.js',
		'/admin/web/js/sb-admin-2.js',
    ];
    public $depends = [
        //'yii\web\YiiAsset',
        //'yii\bootstrap\BootstrapAsset',
    ];
}
