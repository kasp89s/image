<?php
use yii\helpers\Html;
use yii\helpers\Url;
?>
<div class="middle">
    <div class="container clearfix">
             <div class="column-left">
				<div class="block-main alb clearfix">
					<main class="content" role="main">
						<h1><?= $album->title?>: <?= count($images)?> <?= \Yii::t('app', 'images')?></h1>
					</main>
					<?php if (!empty($images)):?>
						<div class="album-photo">
						<?php foreach ($images as $image):?>
							<figure>
								<a href="javascript:void(0)">
									<img src="<?= $image->thumb160?>" alt="<?= $image->title?>" />
								</a>
							</figure>
						<?php endforeach;?>
						</div>
					<?php endif;?>
				</div>
            </div>
        <?= $this->render('rightColumn', ['album' => $album]); ?>
    </div>
</div>
<?= $this->render('modals', ['images' => $images, 'album' => $album]); ?>
