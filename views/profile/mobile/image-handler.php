<?php
use app\models\User;
?>
<div class="left-mobile">
    <header class="header" role="banner">
        <div class="container clearfix">
            <div class="l-header">
                <a href="javascript:void(0)" class="main-link-menu"></a>

            </div>
            <div class="r-header">
                <a href="javascript:void(0)" class="add-t-menu"></a>
                <ul class="ad-t-menu">
                    <li><a href="#main-poop-titl" class="inline"><?= \Yii::t('app', 'edit title/description')?></a></li>
                    <li><a href="#we-are-read" class="inline"><?= \Yii::t('app', 'submit to gallery')?></a></li>
                    <li><a href="javascript:void(0)" class="delt"><?= \Yii::t('app', 'delete')?></a></li>
                </ul>
            </div>
            <div class="c-header">
                <div class="logo">
                    <a href="/"><img src="/images/logo.png" alt="" /></a>
                </div>
            </div>
        </div>
    </header><!--.header-->
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
</div>
<div class="right-mobile">
    <?php if(!empty($startImages)):?>
    <div class="scroll-pane">
        <?php foreach ($startImages as $i):?>
        <article class="active">
            <a href="/profile/image-handler/<?= $i->id ?>">
                <img src="<?= $i->thumb90 ?>" height="106" alt="" />
                <div class="r-txt-mob">
                    <p><?= $i->title ?></p>
                    <span class="replies-m"></span>
                </div>
            </a>
        </article>
        <?php endforeach;?>
    </div>
    <?php endif;?>
</div>

<?= $this->render('//mobile/block/main_left'); ?>
<?= $this->render('//mobile/block/modals', ['images' => [$image]]); ?>