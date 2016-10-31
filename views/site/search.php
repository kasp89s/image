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
            <p><?= \Yii::t('app', 'Found')?> <?= number_format(count($posts))?> <?= \Yii::t('app', 'results for')?> <?= $_GET['request']?>, <?= \Yii::t('app', 'sorted by')?> </p>
            <div class="link-menu">
                <?php if (empty($_GET['sort']) || $_GET['sort'] == 'date') :?>
                    <a href="javascript:void(0)" class=""><?= \Yii::t('app', 'newest first')?></a>,
                <?php endif;?>
                <?php if (!empty($_GET['sort']) && $_GET['sort'] == 'score') :?>
                    <a href="javascript:void(0)" class=""><?= \Yii::t('app', 'highest scoring')?></a>,
                <?php endif;?>
                <?php if (!empty($_GET['sort']) && $_GET['sort'] == 'relevant') :?>
                    <a href="javascript:void(0)" class=""><?= \Yii::t('app', 'most relevant')?></a>,
                <?php endif;?>
                <ul style="display: none;">
                    <?php if (!empty($_GET['sort']) && $_GET['sort'] != 'date') :?>
                        <li><a href="?<?= http_build_query(array_merge($_GET, ['sort' => 'date']))?>"><?= \Yii::t('app', 'newest first')?></a></li>
                    <?php endif;?>
                    <?php if (empty($_GET['sort']) || $_GET['sort'] != 'score') :?>
                        <li><a href="?<?= http_build_query(array_merge($_GET, ['sort' => 'score']))?>"><?= \Yii::t('app', 'highest scoring')?></a></li>
                    <?php endif;?>
                    <?php if (empty($_GET['sort']) || $_GET['sort'] != 'relevant') :?>
                        <li><a href="?<?= http_build_query(array_merge($_GET, ['sort' => 'relevant']))?>"><?= \Yii::t('app', 'most relevant')?></a></li>
                    <?php endif;?>
                </ul>
            </div>
            <div class="link-menu">
                <?php if (empty($_GET['time']) || $_GET['time'] == 'month') :?>
                    <a href="javascript:void(0)"><?= \Yii::t('app', 'this month')?></a>
                <?php endif;?>
                <?php if (!empty($_GET['time']) && $_GET['time'] == 'all') :?>
                    <a href="javascript:void(0)"><?= \Yii::t('app', 'all time')?></a>
                <?php endif;?>
                <?php if (!empty($_GET['time']) && $_GET['time'] == 'today') :?>
                    <a href="javascript:void(0)"><?= \Yii::t('app', 'today')?></a>
                <?php endif;?>
                <?php if (!empty($_GET['time']) && $_GET['time'] == 'week') :?>
                    <a href="javascript:void(0)"><?= \Yii::t('app', 'this week')?></a>
                <?php endif;?>
                <?php if (!empty($_GET['time']) && $_GET['time'] == 'year') :?>
                    <a href="javascript:void(0)"><?= \Yii::t('app', 'this year')?></a>
                <?php endif;?>
                <ul>
                    <?php if (empty($_GET['time']) || $_GET['time'] != 'all') :?>
                        <li><a href="?<?= http_build_query(array_merge($_GET, ['time' => 'all']))?>"><?= \Yii::t('app', 'all time')?></a></li>
                    <?php endif;?>
                    <?php if (empty($_GET['time']) || $_GET['time'] != 'today') :?>
                        <li><a href="?<?= http_build_query(array_merge($_GET, ['time' => 'today']))?>"><?= \Yii::t('app', 'today')?></a></li>
                    <?php endif;?>
                    <?php if (!empty($_GET['time']) && $_GET['time'] != 'month') :?>
                        <li><a href="?<?= http_build_query(array_merge($_GET, ['time' => 'month']))?>"><?= \Yii::t('app', 'this month')?></a></li>
                    <?php endif;?>
                    <?php if (empty($_GET['time']) || $_GET['time'] != 'week') :?>
                        <li><a href="?<?= http_build_query(array_merge($_GET, ['time' => 'week']))?>"><?= \Yii::t('app', 'this week')?></a></li>
                    <?php endif;?>
                    <?php if (empty($_GET['time']) || $_GET['time'] != 'year') :?>
                        <li><a href="?<?= http_build_query(array_merge($_GET, ['time' => 'year']))?>"><?= \Yii::t('app', 'this year')?></a></li>
                    <?php endif;?>
                </ul>
            </div>
        </div>
        <div class="c-content">
            <div class="galery-main">
                <?php if (!empty($posts)):?>
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
