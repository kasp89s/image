<?php
use yii\helpers\Html;
?>
<?= $this->render('//mobile/block/header'); ?>
<div class="middle">
    <div class="container">
        <div class="upload-album">
            <div class="logo-upload">
                <p onclick="$('#inputFile').trigger('click')" style="cursor: pointer;"><b><?= \Yii::t('app', 'Upload from device')?></b></p>
                <input type="file" class="inputFile" id="inputFile" onchange="fileUpload('inputFile');" multiple="" style="display: none;">
                <p class="txt-center"><?= \Yii::t('app', 'or')?></p>
            </div>
                <?= Html::beginForm('/upload-mobile', 'post', ['class' => 'upload-form']); ?>
                <p><input type="text" placeholder="<?= \Yii::t('app', 'Paste Web URLs')?>" onchange="toDataUrl(this.value)"/></p>
                <div class="upload-post-data" style="display: none;">
                    <div class="clearfix">
                        <div class="l-upload">
                            <label class="publish-box">
                                <?= Html::input('checkbox', 'publish', 1)?>
                                <?= \Yii::t('app', 'Publish to QRUTO')?>
                            </label>
                            <label class="album-box">
                                <?= Html::input('checkbox', 'album', 1)?>
                                <?= \Yii::t('app', 'Create Album')?>
                            </label>
                        </div>
                        <a href="javascript:void(0);" class="btn n-bt start-upload"><?= \Yii::t('app', 'UPLOAD')?></a>
                    </div>
                    <div class="album-upload-fields" style="display: none;">
                        <p>
                            <?= Html::input('text', 'UserAlbum[title]', null, ['placeholder' => \Yii::t('app', 'Album Title (optional)')])?>
                        </p>
                        <p>
                            <?= Html::input('text', 'UserAlbum[description]', null, ['placeholder' => \Yii::t('app', 'Album Description (optional)')])?>
                        </p>
                    </div>
                    <div class="publish-upload-fields" style="display: none;">
                        <p>
                            <?= Html::input('text', 'UserPost[title]', null, ['placeholder' => \Yii::t('app', 'Post title *')])?>
                        </p>
                        <p>
                            <?= Html::input('text', 'tags', null, ['placeholder' => \Yii::t('app', 'Tags (optional)')])?>
                        </p>
                        <div class="public-selector">
                            <?= Html::input('hidden', 'UserPost[topic]', null, ['id' => 'topic-id'])?>
                            <a href="javascript:void(0);"><?= \Yii::t('app', 'Category')?>*</a>
                            <ul>
                                <?php foreach (\Yii::$app->params['topicValues'] as $key => $value):?>
                                    <li data-id="<?= $key?>"><a href="javascript:void(0)"><?= \Yii::t('app', $value['title'])?></a></li>
                                <?php endforeach;?>
                            </ul>
                        </div>
                        <p><label><?= Html::input('checkbox', 'UserPost[mature]', 1)?> <?= \Yii::t('app', 'Mature content')?></label></p>
                    </div>
                </div>
            <?= Html::endForm();?>
            <div class="image-upl" style="display: none;">
                <div class="head-image-upl">
                    <i style="width:0%" data-percent="0"></i>
                    <p><?= \Yii::t('app', 'Uploading')?> <span class="upload-image-count"></span> <?= \Yii::t('app', 'images')?></p>
                </div>
                <ul>
                </ul>
            </div>
        </div>
    </div>
</div>
<?= $this->render('//mobile/block/main_left'); ?>