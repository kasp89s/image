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
        <div class="figure
<?= (isset($_SESSION['likePost'][$post->id]) && $_SESSION['likePost'][$post->id] == 'inc') ? 'es-vote active' : ''?>
<?= (isset($_SESSION['likePost'][$post->id]) && $_SESSION['likePost'][$post->id] == 'dec') ? 'no-vote active' : ''?>
">
            <div class="c-figure">
                <a href="<?= Url::to('/' . $post->url)?>">
                    <img src="<?= $image?>" alt="" />
                </a>
                <div class="over" data-count="<?= $post->likeCount?>">
                    <a href="javascript:void(0)"
                       class="link-top <?= (isset($_SESSION['likePost'][$post->id]) && $_SESSION['likePost'][$post->id] == 'inc') ? 'active' : ''?>"
                       onclick="like('<?= $post->id?>', 'inc', this)"></a>
                    <a href="javascript:void(0)"
                       class="link-bot <?= (isset($_SESSION['likePost'][$post->id]) && $_SESSION['likePost'][$post->id] == 'dec') ? 'active' : ''?>"
                       onclick="like('<?= $post->id?>', 'dec', this)"></a>
                    <span class="view"><?= $post->likeCount?></span>
                </div>
            </div>
            <div class="toltip">
                <div class="txt-toltip">
                    <p><?= $post->title?></p>
                </div>
                <p class="txt-right"><?= $post->viewCount?> <?= \Yii::t('app', 'views')?></p>
            </div>
        </div>
    <? endforeach;?>
<? endif;?>