<?php
use yii\helpers\Html;
use yii\helpers\Url;
?>
<div class="right-column">
    <div class="block-main">
        <header><?= \Yii::t('app', 'Image Options')?></header>
        <div class="c-block-main">
            <ul class="menu-right">
                <li><a href="<?= Url::to('/browse')?>"><?= \Yii::t('app', 'Browse all images')?></a></li>
                <li><a href="<?= Url::to('/edit')?>"><?= \Yii::t('app', 'Edit image titles or descriptions')?></a></li>
                <li><a href="<?= Url::to('/rearrange')?>"><?= \Yii::t('app', 'Rearrange images')?></a></li>
                <li><a href="javascript:void(0)" class="edit-image"><?= \Yii::t('app', 'Edit images')?></a></li>
                <li><a href="#change-album-poop" class="inline"><?= \Yii::t('app', 'Change album settings')?></a></li>
            </ul>
            <a href="<?php if (!\Yii::$app->user->isGuest):?>#form-center<?php else:?>#sign-in<?php endif;?>" class="share-btn sh inline"><?= \Yii::t('app', 'Share with the community')?></a>

            <ul class="who-album">
                <li><a href="<?= Url::to('/site/remove-album/' . $album->id)?>" class="del-album"><span><?= \Yii::t('app', 'Delete Album')?></span></a></li>
                <li><a href="<?= Url::to('/site/download-album/' . $album->id)?>" class="down-album"><span><?= \Yii::t('app', 'Download Images')?></span></a></li>
            </ul>
        </div>
    </div>
    <div class="block-main">
        <header><?= \Yii::t('app', 'Share this album')?></header>
        <div class="c-block-main">
            <p><?= \Yii::t('app', 'Share link')?></p>
            <div class="inp-link">
                <a href="javascript:void(0)" data-clipboard-target="#share-album" class="copy-link"><span><?= \Yii::t('app', 'copy')?></span></a>
                <input id="share-album" type="text" value="http://<?= Yii::$app->getRequest()->serverName?>/a/<?= $album->url?>" readonly="readonly" />
            </div>
            <p><?= \Yii::t('app', 'Embed in HTML')?></p>
            <div class="inp-link">
                <a href="javascript:void(0)" data-clipboard-target="#embed-album" class="copy-link"><span><?= \Yii::t('app', 'copy')?></span></a>
                <input type="text" id="embed-album" value='<a href="http://<?= Yii::$app->getRequest()->serverName?>/a/<?= $album->url?>"><?= $album->title?></a>' readonly="readonly" />
            </div>
        </div>
    </div>
    <div class="baner-right">
        <a href="#"><img src="images/r5.jpg" alt="" /></a>
    </div>
</div>
