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
                                <h1><?= \Yii::t('app', 'Gallery favorites')?></h1>
                            </div>
                            <div class="r-profile-head">
                                <ul>
                                    <li class="<?= (empty($_GET['sort_date']) && empty($_GET['sort_like']) || !empty($_GET['sort_date']) && $_GET['sort_date'] == 'desc') ? 'active' : ''?>">
                                        <a href="?<?= http_build_query(['sort_date' => 'desc'])?>"><?= \Yii::t('app', 'Newest')?></a>
                                    </li>
                                    <li class="<?= (!empty($_GET['sort_date']) && $_GET['sort_date'] == 'asc') ? 'active' : ''?>">
                                        <a href="?<?= http_build_query(['sort_date' => 'asc'])?>"><?= \Yii::t('app', 'Oldest')?></a>
                                    </li>
                                    <li class="<?= (!empty($_GET['sort_like']) && $_GET['sort_like'] == 'desc') ? 'active' : ''?>">
                                        <a href="?<?= http_build_query(['sort_like' => 'desc'])?>"><?= \Yii::t('app', 'Best')?></a>
                                    </li>
                                    <li class="<?= (!empty($_GET['sort_like']) && $_GET['sort_like'] == 'asc') ? 'active' : ''?>">
                                        <a href="?<?= http_build_query(['sort_like' => 'asc'])?>"><?= \Yii::t('app', 'Worst')?></a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="c-profile">
                            <?php if (!empty($user->favoritesDesc)):?>
                                <?php
                                if (!empty($_GET['sort_date']) && $_GET['sort_date'] == 'asc') {
                                    $posts = $user->favoritesAsc;
                                } elseif (!empty($_GET['sort_like']) && $_GET['sort_like'] == 'asc') {
                                    $posts = $user->favoritesWorst;
                                } elseif (!empty($_GET['sort_like']) && $_GET['sort_like'] == 'desc') {
                                    $posts = $user->favoritesBest;
                                } else {
                                    $posts = $user->favoritesDesc;
                                }
                                ?>
                            <div class="galery-main">
                                <?php foreach ($posts as $post):?>
                                    <?php
                                    $image = !empty($post->images[0]->album) ? $post->images[0]->album->cover : $post->images[0]->thumb160;
                                    ?>
                                <div class="figure">
                                    <div class="c-figure">
                                        <a href="<?= Url::to('/' . $post->url)?>">
                                            <img src="<?= $image?>" alt="<?= $post->title?>" />
                                        </a>
                                    </div>
                                    <div class="toltip">
                                        <p><?= $post->title?></p>
                                        <p class="help-toltip"><?= \Yii::t('app', 'Likes')?>: <?= number_format($post->likeCount)?>
                                            <?= \Yii::t('app', 'Views')?>: <?= number_format($post->viewCount)?>  <?= User::changeDate($post->date)?></p>
                                    </div>
                                </div>
                                <?php endforeach;?>
                            </div>
                            <?php endif;?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="right-column">
            <?php if (!\Yii::$app->user->isGuest):?>
                <?= $this->render('block/selfProfileMenu', []); ?>
            <?php else:?>
                <?= $this->render('block/guestProfileMenu', ['user' => $user]); ?>
            <?php endif;?>
            <div class="baner-right">
                <a href="#"><img src="images/r5.jpg" alt="" /></a>
            </div>
        </div>
    </div>
</div>
