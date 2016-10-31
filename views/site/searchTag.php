<?php
use yii\helpers\Html;
use yii\helpers\Url;
?>
<div class="middle">
    <div class="container">
        <!--        <div class="nav-main fawor">-->
        <!--            <p>--><?//= \Yii::t('app', 'Request')?><!--:</p>-->
        <!--            <div class="link-menu">-->
        <!--                <a href="javascript:void(0)">--><?//= $_GET['request']?><!--</a>-->
        <!--            </div>-->
        <!--        </div>-->
        <div class="nav-main fawor">
            <p><?= \Yii::t('app', 'Found')?> <?= number_format(count($tag->postTags))?> <?= \Yii::t('app', 'results for tag')?> <?= $_GET['tag']?> </p>
        </div>
        <div class="c-content">
            <div class="galery-main">
                <?php if (!empty($tag->postTags)):?>
                    <?php foreach ($tag->postTags as $postTag):?>
                        <?php
                        $post = $postTag->post;
                        $image = isset($post->images[0]->album->cover) ? $post->images[0]->album->cover : $post->images[0]->thumb160;
                        ?>
                        <div class="figure">
                            <div class="c-figure">
                                <a href="<?= Url::to('/' . $post->url)?>">
                                    <img src="<?= $image?>" alt="" />
                                </a>
                                <div class="over">
                                    <a href="javascript:void(0)" class="link-top"></a>
                                    <a href="javascript:void(0)" class="link-bot"></a>
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
            </div>
        </div>
    </div>
</div>
