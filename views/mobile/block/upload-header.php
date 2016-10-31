<?php
use yii\helpers\Url;
?>
<header class="header navbar navbar-default" role="banner">
    <div class="container clearfix">
        <div class="l-header">
            <a href="javascript:void(0)" class="main-link-menu"></a>

        </div>
        <div class="r-header">
            <?php if(!empty($initial)):?>
            <a href="javascript:void(0)" class="add-t-menu"></a>
            <ul class="ad-t-menu">
<!--                <li><a href="#we-are-read" class="inline">--><?//= \Yii::t('app', 'open image in tab')?><!--</a></li>-->
                <li><a href="#main-poop-titl" class="inline"><?= \Yii::t('app', 'edit title/description')?></a></li>
                <li><a href="#we-are-read" class="inline"><?= \Yii::t('app', 'submit to gallery')?></a></li>
                <li><a href="javascript:void(0)" class="delt"><?= \Yii::t('app', 'delete')?></a></li>
            </ul>
            <?php else:?>
                <a href="<?= Url::to('/image-upload')?>" class="add-download"></a>
            <?php endif;?>
        </div>

        <div class="c-header">
            <div class="logo">
                <a href="/"><img src="/images/logo.png" alt="" /></a>
            </div>
        </div>
    </div>
</header><!--.header-->