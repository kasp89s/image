<?php
use yii\helpers\Html;
use yii\helpers\Url;
?>
<div class="middle">
    <div class="container clearfix">
        <div class="column-left">
            <div class="block-main mn">
                <div class="head-main clearfix">
                    <h1><?= \Yii::t('app', 'Edit images titles or descriptions')?></h1>
                    <div class="r-head-main">
                        <a href="javascript:void(0)" class="btn edit-save" onclick="$('.form-edit-album').submit()"><?= \Yii::t('app', 'Save changes')?></a>
                        <?php if (!empty($album)):?>
                            <a href="/a/<?= $album->url?>" class="btn red-bg"><?= \Yii::t('app', 'Cancel')?></a>
                        <?php else: ?>
                            <a href="/<?= $images[0]->url?>" class="btn red-bg"><?= \Yii::t('app', 'Cancel')?></a>
                        <? endif;?>
                    </div>
                </div>
                <div class="form-deskr">
                <?php if (!empty($images)):?>
                    <?= Html::beginForm('site/changeimage', 'post', ['class' => 'form-edit-album']); ?>
                    <?php foreach ($images as $image):?>
                        <div class="section-form clearfix">
                            <figure>
                                <a href="<?= $image->original?>" class="ajax">
                                    <img src="<?= $image->thumb160?>" alt="<?= $image->title?>" />
                                </a>
                            </figure>
                            <div class="r-section-form">
                                <?= Html::input('hidden', 'image'.$image->id.'[UserImage][id]', $image->id)?>
                                <?= Html::input('text', 'image'.$image->id.'[UserImage][title]', $image->title, ['placeholder' => \Yii::t('app', 'Title')])?>
                                <?= Html::textarea('image'.$image->id.'[UserImage][description]', $image->description, ['placeholder' => \Yii::t('app', 'Description')]) ?>
                            </div>
                        </div>
                    <?php endforeach;?>
                    <?= Html::endForm();?>
                <?php endif;?>
                </div>
            </div>
        </div>
        <?php if (!empty($album)):?>
        <?= $this->render('rightColumn', ['album' => $album]); ?>
        <?php else: ?>
        <div class="right-column">
        <?= $this->render('rightColumnSingle', ['image' => $images[0]]); ?>
        </div>
        <? endif;?>
    </div>
</div>
<?php if (!empty($album)):?>
<?= $this->render('modals', ['images' => $images, 'album' => $album]); ?>
<?php else: ?>
    <?= $this->render('singleModals', ['image' => $image, 'images' => [$image]]); ?>
<?php endif;?>
