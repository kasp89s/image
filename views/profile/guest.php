<?php
use yii\helpers\Html;
use yii\helpers\Url;
use app\models\User;
?>
<div class="middle">
<div class="container clearfix">
<div class="column-left">
<div class="block-main">
<div class="box visible">
    <div class="profile">
        <div class="profile-head clearfix">
            <div class="l-profile-head">
                <h1><?= \Yii::t('app', 'Submited images')?></h1>
            </div>
            <div class="r-profile-head">
                <ul>
                    <li class="active"><a href="#"><?= \Yii::t('app', 'Newest')?></a></li>
                    <li><a href="#"><?= \Yii::t('app', 'Oldest')?></a></li>
                </ul>
            </div>
        </div>
        <div class="c-profile">
            <div class="galery-main">
                <?php if (!empty($images)):?>
                    <?php foreach ($images as $image):?>
                        <div class="figure">
                            <div class="c-figure">
                                <a href="<?= Url::to('/' . $image->url)?>">
                                    <img src="<?= $image->thumb160?>" alt="<?= $image->title?>" />
                                </a>
                            </div>
                            <div class="toltip">
                                <p><?= $image->title?></p>
                                <p class="help-toltip">Likes: <?= number_format($image->like)?>  Views: <?= number_format($image->views)?>  <?= User::changeDate($image->date)?></p>
                            </div>
                        </div>
                        <?php endforeach;?>
                <?php endif;?>
            </div>
        </div>
    </div>
</div>
</div>
</div>
<div class="right-column">
    <?= $this->render('block/guestProfileMenu', []); ?>
    <div class="baner-right">
        <a href="#"><img src="images/r5.jpg" alt="" /></a>
    </div>
</div>
</div>
</div>
