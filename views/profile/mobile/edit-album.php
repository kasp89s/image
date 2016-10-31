<?php
use yii\helpers\Html;
?>
<?= $this->render('//mobile/block/header'); ?>
<div class="middle">
    <div class="container">
        <div class="new-edit-album">
            <?= Html::beginForm('', 'post', ['class' => 'edit-album-form']); ?>
                <div class="top-edit-album">
                    <p>
                        <?= Html::input('text', 'UserAlbum[title]', $album->title, ['placeholder' => \Yii::t('app', 'Album Title (optional)')])?>
                    </p>
                    <p>
                        <?= Html::input('text', 'UserAlbum[description]', $album->description, ['placeholder' => \Yii::t('app', 'Album Description (optional)')])?>
                    </p>
                </div>
                <div class="tabs-edit-album">
                    <?= Html::input('hidden', 'UserAlbum[type]', $album->type)?>
                    <p><?= \Yii::t('app', 'Privacy')?>:</p>
                    <ul>
                        <li <?php if($album->type == 'Public'):?>class="active"<?php endif;?>>
                            <a href="javascript:void(0)" data-type="Public"><?= \Yii::t('app', 'Public')?></a>
                        </li>
                        <li <?php if($album->type == 'Hidden'):?>class="active"<?php endif;?>>
                            <a href="javascript:void(0)" data-type="Hidden"><?= \Yii::t('app', 'Hidden')?></a>
                        </li>
                        <li <?php if($album->type == 'Secret'):?>class="active"<?php endif;?>>
                            <a href="javascript:void(0)" data-type="Secret"><?= \Yii::t('app', 'Secret')?></a>
                        </li>
                    </ul>
                </div>
                <?php if(!empty($album->images)):?>
                <div class="c-edit-album">
                    <p><?= \Yii::t('app', 'Select images')?></p>
                    <div class="c-content">
                        <div class="galery-main">
                            <?php foreach ($album->images as $image):?>
                            <div class="figure" data-id="<?= $image->id?>">
                                <div class="c-figure">
                                    <a href="javascript:void(0)">
                                        <img src="<?= $image->thumb160?>" alt="<?= $image->title?>" />
                                    </a>
                                    <div class="over">
                                        <p><?= $image->title?></p>
                                        <span class="view"><?= number_format($image->views)?></span>
                                        <span class="laik-view"><?= number_format($image->like)?></span>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach;?>
                        </div>
                    </div>
                </div>
                <?php endif;?>
                <p class="bot-edit-alb">
                    <input class="save-album-btn" type="submit" value="<?= \Yii::t('app', 'Save Album')?>" />
                    <a href="javascript:void(0)" class="delt" style="display: none;"><?= \Yii::t('app', 'Delete')?></a>
                    <?php if(!empty($album->images)):?>
                    <input class="inline save-publish" href="#we-are-read" type="button" value="<?= \Yii::t('app', 'Publish to QRUTO')?>" />
                    <?php endif;?>
                </p>
            <?= Html::endForm();?>
        </div>
    </div>
</div>
<?= $this->render('//mobile/block/main_left'); ?>
<?= $this->render('//mobile/block/modals', ['images' => $album->images]); ?>
