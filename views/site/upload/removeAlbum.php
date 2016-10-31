<?php
use yii\helpers\Html;
use yii\helpers\Url;
?>
    <div class="middle">
        <div class="container clearfix">
            <div class="column-left">
				<div class="block-main">
					<div class="delete-photo">
						<h1><?= \Yii::t('app', 'Are you sure you want delete this album which {count} images', ['count' => count($album->images)])?>? <?= \Yii::t('app', 'It will be gone forever')?>!</h1>
						<p class="txt-center">
							<a href="/a/<?= $album->url?>" class="btn red-bg"><?= \Yii::t('app', 'No')?></a>
							<a href="javascript:void(0)" class="btn yes-remove"><?= \Yii::t('app', 'Yes')?></a>
						</p>
					</div>
				</div>
			</div>
        </div>
		<?= Html::beginForm('/site/remove-album/' . $album->id, 'post', ['class' => 'form-remove-album']); ?>
		<?= Html::input('hidden', 'confirm', true)?>
		<?= Html::endForm();?>
    </div>
