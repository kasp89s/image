<?php
use yii\helpers\Html;
use yii\helpers\Url;
?>
<div class="block-main">
    <header><?= $this->params['user']->username?></header>
    <div class="c-block-main">
        <ul class="menu-right-main">
            <li class="<?= (Yii::$app->controller->action->id == 'comments') ? 'active' : ''?>">
                <a href="<?= Url::to('/user/' . $this->params['user']->username . '/comments')?>"><?= \Yii::t('app', 'Comments')?></a></li>
            <li class="<?= (Yii::$app->controller->action->id == 'gallery') ? 'active' : ''?>">
                <a href="<?= Url::to('/user/' . $this->params['user']->username)?>"><?= \Yii::t('app', 'Submited images')?></a></li>
            <li class="<?= (Yii::$app->controller->action->id == 'favorites') ? 'active' : ''?>">
                <a href="<?= Url::to('/user/' . $this->params['user']->username . '/favorites')?>"><?= \Yii::t('app', 'Gallery favorites')?></a></li>
            <li class="<?= (Yii::$app->controller->action->id == 'replies') ? 'active' : ''?>">
                <a href="<?= Url::to('/user/' . $this->params['user']->username . '/replies')?>"><?= \Yii::t('app', 'Replies')?></a></li>
        </ul>
        <div class="descript">
            <textarea class="about-message"><?= $this->params['user']->about?></textarea>
        </div>
        <ul class="who-list">
            <li><?= \Yii::t('app', 'Member since')?> <?= date('Y', strtotime($this->params['user']->date))?></li>
            <li><?= \Yii::t('app', 'Likes')?>: <?= number_format($this->params['user']->likeCount)?></li>
            <li><?= \Yii::t('app', 'Images')?>: <?= number_format($this->params['user']->imageCount)?></li>
            <li><?= \Yii::t('app', 'Comments')?>: <?= number_format($this->params['user']->commentCount)?></li>
        </ul>
    </div>
</div>