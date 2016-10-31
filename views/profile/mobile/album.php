<?php
use yii\helpers\Url;
use yii\helpers\Html;
?>
<?= $this->render('//mobile/block/header'); ?>

<div class="middle">
    <div class="container">
        <div class="mobile-myalbums">
            <p class="txt-center"><a href="<?= Url::to('/profile/create-album')?>" class="btn"><?= \Yii::t('app', 'New Album')?></a></p>
            <ul>
                <?php foreach ($this->params['user']->albums as $album):?>
                <li class="clearfix">
                    <figure><img src="<?= $album->cover?>" alt="<?= $album->title?>" /></figure>
                    <article>
                        <p><a href="http://<?= $_SERVER['HTTP_HOST'] . Url::to('/a/' . $album->url)?>"><?= $album->title?></a></p>
                    </article>
                    <div class="instal">
                        <a href="<?= Url::to('/profile/edit-album/' . $album->id)?>"></a>
                    </div>
                </li>
                <?php endforeach;?>
            </ul>
        </div>
    </div>
</div>

<?= $this->render('//mobile/block/main_left'); ?>
