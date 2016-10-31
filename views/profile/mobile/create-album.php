<?php
use yii\helpers\Html;
?>
<?= $this->render('//mobile/block/header'); ?>
<div class="middle">
    <div class="container">
        <div class="new-edit-album">
            <?= Html::beginForm('', 'post', ['class' => 'new-album-form']); ?>
            <div class="top-edit-album">
                <p>
                    <?= Html::input('text', 'UserAlbum[title]', null, ['placeholder' => \Yii::t('app', 'Album Title (optional)')])?>
                </p>
                <p>
                    <?= Html::input('text', 'UserAlbum[description]', null, ['placeholder' => \Yii::t('app', 'Album Description (optional)')])?>
                </p>
            </div>
            <div class="tabs-edit-album">
                <?= Html::input('hidden', 'UserAlbum[type]', 'Public')?>
                <p><?= \Yii::t('app', 'Privacy')?>:</p>
                <ul>
                    <li class="active">
                        <a href="javascript:void(0)" data-type="Public"><?= \Yii::t('app', 'Public')?></a>
                    </li>
                    <li>
                        <a href="javascript:void(0)" data-type="Hidden"><?= \Yii::t('app', 'Hidden')?></a>
                    </li>
                    <li>
                        <a href="javascript:void(0)" data-type="Secret"><?= \Yii::t('app', 'Secret')?></a>
                    </li>
                </ul>
            </div>
            <p class="bot-edit-alb">
                <input class="save-album-btn" type="submit" value="<?= \Yii::t('app', 'Save Album')?>" />
            </p>
            <?= Html::endForm();?>
        </div>
    </div>
</div>
<?= $this->render('//mobile/block/main_left'); ?>
