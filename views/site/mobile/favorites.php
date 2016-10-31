<?php
use yii\helpers\Html;
use yii\helpers\Url;
?>
<?= $this->render('//mobile/block/header'); ?>

<div class="middle">
<div class="container">
<div class="nav-main fawor">
    <p><?= \Yii::t('app', 'Your favorite images, sorted by')?></p>
    <div class="link-menu">
        <a href="javascript:void(0)"><?= (empty($_GET['sort_date']) || $_GET['sort_date'] == 'desc') ? \Yii::t('app', 'newest') : \Yii::t('app', 'oldest')?></a>
        <ul>
            <?php if (empty($_GET['sort_date']) || $_GET['sort_date'] == 'desc'):?>
                <li><a href="?<?= http_build_query(['sort_date' => 'asc'])?>"><?= \Yii::t('app', 'oldest')?></a></li>
            <?php endif;?>
            <?php if (!empty($_GET['sort_date']) && $_GET['sort_date'] == 'asc'):?>
                <li><a href="?<?= http_build_query(['sort_date' => 'desc'])?>"><?= \Yii::t('app', 'newest')?></a></li>
            <?php endif;?>
        </ul>
    </div>
</div>
<div class="c-content">
<div class="galery-main">
<?php if (!empty($this->params['user'])):?>
    <?php
        if (empty($_GET['sort_date']) || $_GET['sort_date'] == 'desc') {
            $posts = $this->params['user']->favoritesDesc;
        } elseif (!empty($_GET['sort_date']) && $_GET['sort_date'] == 'asc') {
            $posts = $this->params['user']->favoritesAsc;
        }
    ?>
    <?php foreach ($posts as $post):?>
        <?php
        $image = isset($post->images[0]->album->cover) ? $post->images[0]->album->cover : $post->images[0]->thumb160;
        ?>
        <div class="figure">
            <div class="c-figure">
                <a href="<?= Url::to('/' . $post->url)?>">
                    <img src="<?= $image?>" alt="" />
                </a>
                <div class="over">
                    <p><?= $post->title?></p>
                    <span class="view"><?= $post->viewCount?></span>
                    <span class="laik-view"><?= $post->likeCount?></span>
                </div>
            </div>
        </div>
    <? endforeach;?>
<? endif;?>
</div>
</div>
</div>
</div>
<?= $this->render('//mobile/block/main_left'); ?>