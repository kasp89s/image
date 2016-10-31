<?php
use yii\helpers\Html;
?>
<div style="display:none">
    <div class="main-poop-titl" id="main-poop-titl">
        <h4><?= \Yii::t('app', 'Edit title/description')?></h4>
        <?= Html::beginForm('/site/changeimage', 'post', ['class' => 'form-edit-album']); ?>
        <?php foreach ($images as $image):?>
            <?= Html::input('hidden', 'image'.$image->id.'[UserImage][id]', $image->id)?>
            <p><?= Html::input('text', 'image'.$image->id.'[UserImage][title]', $image->title, ['placeholder' => \Yii::t('app', 'Title(optional)')])?></p>
            <p><?= Html::input('text', 'image'.$image->id.'[UserImage][description]', $image->description, ['placeholder' => \Yii::t('app', 'Description (optional)')]) ?></p>
        <?php endforeach;?>
            <p class="txt-center"><input type="submit" value="<?= \Yii::t('app', 'Save')?>" /></p>
        <?= Html::endForm();?>
    </div>
</div>

<div style="display:none">
    <div class="we-are-read" id="we-are-read">
        <h4><?= \Yii::t('app', 'We are ready to share your post!')?></h4>
        <?= Html::beginForm('/ajax/share', 'post', ['class' => 'form-share-album']); ?>
            <?php if (!empty($images)):?>
                <?php foreach ($images as $image):?>
                    <?= Html::input('hidden', 'image'.$image->id.'[UserImage][id]', $image->id)?>
                <?php endforeach;?>
            <?php endif;?>
            <p>
                <?= Html::input('text', 'UserPost[title]', null, ['placeholder' => \Yii::t('app', 'Post title*')])?>
            </p>
            <p>
                <?= Html::input('text', 'UserPost[tags]', null, ['placeholder' => \Yii::t('app', 'Tags (optionsl)')])?>
            </p>
            <p>
            <select name="UserPost[topic]" class="select">
                <option value=""><?= \Yii::t('app', 'Category')?>*</option>
                <?php foreach (\Yii::$app->params['topicValues'] as $key => $value):?>
                    <option value="<?= $key?>"><?= \Yii::t('app', $value['title'])?></option>
                <?php endforeach;?>
            </select>
            </p>
            <p><label><?= Html::input('checkbox', 'UserPost[mature]', 1)?> <?= \Yii::t('app', 'Mature content')?></label></p>
            <p class="txt-center">
                <input type="submit" value="<?= \Yii::t('app', 'Publish to QRUTO')?>" />
            </p>
        <?= Html::endForm();?>
    </div>
</div>

<!-- delete -->
<div class="delete">
    <p><?= \Yii::t('app', 'Are you sure you want to delete this image?')?></p>
    <p>
        <?php if(count($images) == 1):?>
                <a href="/site/remove-image/<?= $images[0]->id?>" class="bg-red"><?= \Yii::t('app', 'Delete')?></a>
            <?php else:?>
                <a href="javascript:void(0)" class="bg-red"><?= \Yii::t('app', 'Delete')?></a>
        <?php endif;?>
        <a href="javascript:void(0)"><?= \Yii::t('app', 'Cancel')?></a>
    </p>
</div>
<span class="bg-del"></span>
<!-- / delete -->