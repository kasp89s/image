<?php
use yii\helpers\Html;
use yii\helpers\Url;
?>
<div class="middle">
    <div class="container clearfix">
        <div class="column-left">
            <div class="block-main">
                <div class="delete-photo">
                    <h1><?= \Yii::t('app', 'Are you sure you want delete this image? It will be gone forever')?>!</h1>
                    <p class="txt-center">
                        <a href="/<?= $image->url?>" class="btn red-bg"><?= \Yii::t('app', 'No')?></a>
                        <a href="javascript:void(0)" class="btn yes-remove"><?= \Yii::t('app', 'Yes')?></a>
                    </p>
                    <figure>
                        <img src="<?= $image->original?>" alt="<?= $image->title?>">
                    </figure>
                </div>
            </div>
        </div>
        <? if (\Yii::$app->user->isGuest):?>
            <div class="right-column">
                <div class="block-main">
                    <header><?= \Yii::t('app', 'Create MegaSite Account')?></header>
                    <div class="c-block-main">
                        <p><?= \Yii::t('app', 'Register for an account and start having control over the images you upload')?>.</p>
                        <a class="inline" href="#sign-up"><?= \Yii::t('app', 'Register for an account')?></a><p></p>
                    </div>
                </div>
            </div>
        <? endif;?>
    </div>
    <?= Html::beginForm('/site/remove-image/' . $image->id, 'post', ['class' => 'form-remove-album']); ?>
    <?= Html::input('hidden', 'confirm', true)?>
    <?= Html::endForm();?>
</div>
