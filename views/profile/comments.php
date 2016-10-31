<?php
use yii\helpers\Html;
use yii\helpers\Url;
use app\models\User;
unset($_GET['username']);
?>
<div class="middle">
    <div class="container clearfix">
        <div class="column-left">
            <div class="block-main">
                <div class="box visible">
                    <div class="profile">
                        <div class="profile-head clearfix">
                            <div class="l-profile-head">
                                <h1><?= \Yii::t('app', 'Gallery comments')?></h1>
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
                        <?php if (!empty($user->comments)):?>
                        <div class="c-profile">
                            <?php
                                if (!empty($_GET['sort_date']) && $_GET['sort_date'] == 'asc') {
                                    $comments = $user->commentsAsc;
                                } elseif (!empty($_GET['sort_like']) && $_GET['sort_like'] == 'asc') {
                                    $comments = $user->commentsWorst;
                                } elseif (!empty($_GET['sort_like']) && $_GET['sort_like'] == 'desc') {
                                    $comments = $user->commentsBest;
                                } else {
                                    $comments = $user->comments;
                                }
                            ?>
                            <?php foreach ($user->comments as $comment):?>
                                <?php
                                    $image = !empty($comment->post->images[0]->album) ? $comment->post->images[0]->album->cover : $comment->post->images[0]->thumb90;
                                ?>
                                <div class="comments clearfix">
                                    <figure><a href="<?= Url::to('/' . $comment->post->url)?>"><img src="<?= $image?>" alt="" /></a></figure>
                                    <div class="r-comments-galery">
                                        <article>
                                            <div class="head-comm-galer">
                                                <a href="<?= Url::to('/user/' . $user->username)?>"><?= $user->username?></a> <?= User::changeDate($comment->date)?>
                                            </div>
                                            <p><?= $comment->message?></p>
                                        </article>
                                    </div>
                                </div>
                            <?php endforeach;?>
                        </div>
                        <?php endif;?>
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