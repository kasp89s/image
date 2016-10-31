<?php
use yii\helpers\Html;
use yii\helpers\Url;
?>
<div class="middle">
    <div class="container clearfix">
            <div class="column-left">
				<div class="block-main mn">
					<div class="head-main clearfix">
						<h1><?= \Yii::t('app', 'Click and drag an image to change its order')?>.</h1>
						<div class="r-head-main">
							<a href="javascript:void(0)" class="btn rearrange-save"><?= \Yii::t('app', 'Save changes')?></a>
                            <?php if (!empty($album)):?>
                                <a href="/a/<?= $album->url?>" class="btn red-bg"><?= \Yii::t('app', 'Cancel')?></a>
                            <?php else: ?>
                                <a href="/<?= $images[0]->url?>" class="btn red-bg"><?= \Yii::t('app', 'Cancel')?></a>
                            <? endif;?>
						</div>
					</div>
					<?php if (!empty($images)):?>
					<div class="album-photo curs">
						<ul id="sortable">
						<?php foreach ($images as $image):?>
							<li class="ui-state-default" id="item-<?= $image->id?>">
								<img src="<?= $image->thumb160?>" alt="<?= $image->title?>" />
							</li>
						<?php endforeach;?>
						</ul>
					</div>
					<?php endif;?>
				</div>
			</div>
        <?= $this->render('rightColumn', ['album' => $album]); ?>
    </div>
</div>
<?= $this->render('modals', ['images' => $images, 'album' => $album]); ?>
