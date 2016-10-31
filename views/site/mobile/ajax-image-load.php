<?php
use yii\helpers\Html;
use yii\helpers\Url;
?>
<?php if (!empty($posts)):?>
    <?php foreach ($posts as $post):?>
        <?php
        if (empty($post->images[0])) {var_dump($post); exit;}
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
