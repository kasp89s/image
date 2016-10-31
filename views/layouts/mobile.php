<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use app\assets\AppAssetMobile;

AppAssetMobile::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<!--[if IE]><![endif]-->
<!--[if IE 8 ]><html dir="ltr" lang="ru" class="ie8"><![endif]-->
<!--[if IE 9 ]><html dir="ltr" lang="ru" class="ie9"><![endif]-->
<!--[if (gt IE 9)|!(IE)]><!-->
<html lang="<?= Yii::$app->language ?>" xmlns="http://www.w3.org/1999/xhtml">
<!--<![endif]-->
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <?= Html::csrfMetaTags() ?>
    <title><?= \Yii::t('app', \Yii::$app->params['seo']['title'])?></title>
    <meta name="robots" content="follow, index" />
    <meta name="keywords" content="<?= \Yii::t('app', \Yii::$app->params['seo']['keywords'])?>">
    <meta name="description" content="<?= \Yii::t('app', \Yii::$app->params['seo']['description'])?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="/web/js/jquery-1.11.2.min.js"></script>
    <script type="text/javascript">
        var ajaxProcessing = false;
    </script>
    <?php $this->head() ?>
</head>
<body>
<?= $this->render('//site/social'); ?>
<?php $this->beginBody() ?>
<div class="page">

    <?= $content ?>

    <div class="bg-main"></div>
</div><!-- /page -->
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
