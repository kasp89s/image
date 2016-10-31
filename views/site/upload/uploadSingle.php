<?php 
	use yii\helpers\Html;
    use yii\helpers\Url;
    use app\models\User;

$this->registerJsFile('/web/js/jquery.jcarousel.min.js',  ['position' => yii\web\View::POS_END]);
$this->registerJsFile('/web/js/cropper.min.js',  ['position' => yii\web\View::POS_END]);
$this->registerJsFile('/web/js/imageEditor.js',  ['position' => yii\web\View::POS_END]);
?>
<div class="middle">
<div class="container">
 <div class="social-left">
                <ul>
                    <li><a href="https://www.facebook.com/sharer/sharer.php?sdk=joey&u=<?= Yii::$app->getRequest()->serverName?>/i/<?= $image->url?>" class="icon-social ff"
                           onclick="window.open(this.href, 'mywin', 'left=20,top=20,width=600,height=500,toolbar=1,resizable=0'); return false;"></a></li>
                    <li><a href="https://twitter.com/home?status=<?= Yii::$app->getRequest()->serverName?>/i/<?= $image->url?>" class="icon-social tvt"
                           onclick="window.open(this.href, 'mywin', 'left=20,top=20,width=600,height=500,toolbar=1,resizable=0'); return false;"></a></li>
<!--                    <li><a href="#" class="icon-social yah"></a></li>-->
                    <li><a href="http://vk.com/share.php?url=http://<?= Yii::$app->getRequest()->serverName?>/i/<?= $image->url?>" class="icon-social vk"
                           onclick="window.open(this.href, 'mywin', 'left=20,top=20,width=600,height=500,toolbar=1,resizable=0'); return false;"></a></li>
                    <li><a href="http://service.weibo.com/share/share.php?url=http://<?= Yii::$app->getRequest()->serverName?>/i/<?= $image->url?>&language=<?= \Yii::$app->language?>" class="icon-social sl"
                           onclick="window.open(this.href, 'mywin', 'left=20,top=20,width=600,height=500,toolbar=1,resizable=0'); return false;"></a></li>
                    <li><a href="https://plus.google.com/share?url=<?= Yii::$app->getRequest()->serverName?>/i/<?= $image->url?>" class="icon-social gl"
                           onclick="window.open(this.href, 'mywin', 'left=20,top=20,width=600,height=500,toolbar=1,resizable=0'); return false;"></a></li>
                </ul>
            </div>
<div class="photo clearfix">
<div class="column-left">
<div class="block-main">
    <div class="head-photo clearfix">
        <div class="l-head-photo">
            <h1><?= $image->title?></h1>
            <p>
                <?= \Yii::t('app', 'Uploaded')?>
                <?php if (!\Yii::$app->user->isGuest):?>
                <?= \Yii::t('app', 'by')?>
                <a href="<?= Url::to('/user/') . $image->user->username?>"><?= $image->user->username?></a>
                <?php endif;?>
                <?= User::changeDate($image->date)?>
            </p>
        </div>
    </div>
    <div class="figure-photo">
        <div class="c-photo">
            <a href="<?= $image->original?>" class="ajax"><img src="<?= $image->original?>" alt="" /></a>
        </div>
    </div>
    <div class="article-photo">
        <p><?= $image->description?></p>
    </div>
</div>
</div>
<div class="right-column">
    <?php if(!empty($initial)):?>
        <?= $this->render('rightColumnSingle', ['image' => $image]); ?>
    <?php endif;?>
    <div class="block-main">
        <header><?= \Yii::t('app', 'Share this image')?></header>
        <div class="c-block-main">
            <p><?= \Yii::t('app', 'Share link')?></p>
            <div class="inp-link">
                <a href="javascript:void(0)" data-clipboard-target="#share-url" class="copy-link"><span><?= \Yii::t('app', 'copy')?></span></a>
                <input id="share-url" type="text" value="http://<?= Yii::$app->getRequest()->serverName?>/i/<?= $image->url?>" readonly="readonly" />
            </div>
            <p><?= \Yii::t('app', 'Embed in HTML')?></p>
            <div class="inp-link">
                <a href="javascript:void(0)" data-clipboard-target="#embeded-url" class="copy-link"><span><?= \Yii::t('app', 'copy')?></span></a>
                <input id="embeded-url" type="text" value='<img src="<?= $image->original?>" alt="<?= $image->title?>" />' readonly="readonly" />
            </div>
        </div>
    </div>

    <div class="baner-right">
        <a href="#"><img src="images/r5.jpg" alt="" /></a>
    </div>
</div>
</div>
</div>
</div>
<?= $this->render('singleModals', ['image' => $image, 'images' => [$image]]); ?>
