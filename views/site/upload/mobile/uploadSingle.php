<?php 
	use yii\helpers\Html;
    use yii\helpers\Url;
    use app\models\User;
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
                            <li><a href="javascript:void(0)" data-href="http://<?= Yii::$app->getRequest()->serverName?>/i/<?= $image->url?>"><?= \Yii::t('app', 'Link')?></a></li>
                            <li><a href="javascript:void(0)" data-href="<?= $image->original?>"><?= \Yii::t('app', 'Direct Link')?></a></li>
                            <li><a href="javascript:void(0)" data-href='<img src="http://cdn1.qruto.com/uploads/original/Zd5A97i2.jpg" />'><?= \Yii::t('app', 'HTML Image')?></a></li>
                        </ul>
                    </div>
                    <div class="r-t-mobile">
                        http://<?= Yii::$app->getRequest()->serverName?>/i/<?= $image->url?>
                    </div>
                </div>
                <div class="head-photo clearfix">
                    <h1><?= $image->title?></h1>
                    <p><?= User::changeDate($image->date)?></p>
                </div>
                <div class="figure-photo">
                    <div class="c-photo">
                        <a href="<?= $image->original?>"><img src="<?= $image->original?>" alt="<?= $image->original?>" /></a>
                    </div>
                </div>
                <div class="article-photo">
                    <p><?= $image->description?></p>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->render('//mobile/block/main_left'); ?>
<?= $this->render('//mobile/block/modals', ['images' => [$image]]); ?>
