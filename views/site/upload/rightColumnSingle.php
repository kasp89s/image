<?php
use yii\helpers\Html;
use yii\helpers\Url;
?>

    <div class="block-main">
        <header><?= \Yii::t('app', 'Image Options')?></header>
        <div class="c-block-main">
            <ul class="menu-right">
                <li><a href="<?= Url::to('edit')?>"><?= \Yii::t('app', 'Edit Title/Description')?></a></li>
                <li><a href="#get-deletion-link" class="inline"><?= \Yii::t('app', 'Get deletion link')?></a></li>
                <li><a href="javascript:void(0)" class="edit-image"><?= \Yii::t('app', 'Edit image')?></a></li>
            </ul>
            <a href="<?php if (!\Yii::$app->user->isGuest):?>#form-center<?php else:?>#sign-in<?php endif;?>" class="share-btn sh inline"><?= \Yii::t('app', 'Publish to MegaSite')?></a>
            <ul class="who-album">
                <li><a href="<?= Url::to('site/remove-image/' . $image->id)?>" class="del-album"><span><?= \Yii::t('app', 'Delete Image')?></span></a></li>
                <li><a href="<?= Url::to('site/download-image/' . $image->id)?>" class="down-album"><span><?= \Yii::t('app', 'Download Image')?></span></a></li>
            </ul>
        </div>
    </div>
