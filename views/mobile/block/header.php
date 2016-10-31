<?php
use yii\helpers\Url;
?>
<header class="header navbar navbar-default" role="banner">
    <div class="container clearfix">
        <div class="l-header">
            <a href="javascript:void(0)" class="main-link-menu"></a>

        </div>
        <div class="r-header">
            <a href="<?= Url::to('/image-upload')?>" class="add-download"></a>
        </div>
        <div class="c-header">
            <div class="logo">
                <a href="/"><img src="/images/logo.png" alt="" /></a>
            </div>
        </div>
    </div>
</header><!--.header-->