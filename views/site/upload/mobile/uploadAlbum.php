<?php 
	use yii\helpers\Html;
    use yii\helpers\Url;
    use app\models\User;
    $images = $album->images;
?>
<?= $this->render('//mobile/block/upload-header', ['initial' => true]); ?>
<div class="middle">
    <div class="container">
        <div class="block-main">
            <div class="photo">
                <div class="t-mobile-myphoto">
                    <div class="public-selector">
                        <a href="javascript:void(0)" id="active-link"><?= \Yii::t('app', 'Link')?></a>
                        <ul class="list-link-selector">
                            <li><a href="javascript:void(0)" data-href="http://<?= Yii::$app->getRequest()->serverName?>/a/<?= $album->url?>"><?= \Yii::t('app', 'Link')?></a></li>
                            <li><a href="javascript:void(0)" data-href='<a href="http://<?= Yii::$app->getRequest()->serverName?>/a/<?= $album->url?>"><?= $album->title?></a>'><?= \Yii::t('app', 'HTML Embed')?></a></li>
                        </ul>
                    </div>
                    <div class="r-t-mobile">
                        http://<?= Yii::$app->getRequest()->serverName?>/a/<?= $album->url?>
                    </div>
                </div>
                <div class="head-photo clearfix">
                    <h1><?= $album->title?></h1>
                    <p><?= User::changeDate($album->date)?></p>
                </div>
                <?php if (!empty($images)):?>
                    <?php foreach ($images as $image):?>
                        <div class="figure-photo">
                            <div class="c-photo">
                                <a href="<?= $image->original?>"><img src="<?= $image->original?>" alt="<?= $image->title?>" /></a>
                            </div>
                        </div>
                        <div class="article-photo">
                            <p><?= $image->title?></p>
                            <p><?= $image->description?></p>
                        </div>
                    <?php endforeach;?>
                <?php endif;?>
            </div>
        </div>
    </div>
</div>
<?= $this->render('//mobile/block/main_left'); ?>
<?= $this->render('//mobile/block/modals', ['images' => $images]); ?>