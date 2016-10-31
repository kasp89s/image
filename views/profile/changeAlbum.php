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
                        <a href="javascript:void(0)" class="btn edit-save" onclick="$('.form-change-album-images').submit()"><?= \Yii::t('app', 'Save changes')?></a>
                        <a href="<?= Url::to('/profile/album')?>" class="btn red-bg"><?= \Yii::t('app', 'Cancel')?></a>
                    </div>
                </div>
                <div class="form-deskr">
                    <?php if (!empty($images)):?>
                        <?= Html::beginForm('', 'post', ['class' => 'form-change-album-images']); ?>
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
        <?= $this->render('block/rightColumn', []); ?>
    </div>
</div>
<?= $this->render('block/modals', []); ?>
