<?php
use yii\helpers\Html;
use yii\helpers\Url;
?>
<div class="block-main">
    <header><?= $user->username?></header>
    <div class="c-block-main">
        <ul class="menu-right-main">
            <li class="<?= (Yii::$app->controller->action->id == 'comments') ? 'active' : ''?>">
                <a href="<?= Url::to('/user/' . $user->username . '/comments')?>"><?= \Yii::t('app', 'Comments')?></a></li>
            <li class="<?= (Yii::$app->controller->action->id == 'gallery') ? 'active' : ''?>">
                <a href="<?= Url::to('/user/' . $user->username)?>"><?= \Yii::t('app', 'Submited images')?></a></li>
            <li class="<?= (Yii::$app->controller->action->id == 'favorites') ? 'active' : ''?>">
                <a href="<?= Url::to('/user/' . $user->username . '/favorites')?>"><?= \Yii::t('app', 'Gallery favorites')?></a></li>
            </ul>
        <div class="descript">
            <textarea disabled="disabled"><?= $user->about?></textarea>
        </div>
        <ul class="who-list">
            <li><?= \Yii::t('app', 'Member since')?> <?= date('Y', strtotime($user->date))?></li>
            <li><?= \Yii::t('app', 'Likes')?>: <?= number_format($user->likeCount)?></li>
            <li><?= \Yii::t('app', 'Images')?>: <?= number_format($user->imageCount)?></li>
            <li><?= \Yii::t('app', 'Comments')?>: <?= number_format($user->commentCount)?></li>
        </ul>
    </div>
</div>