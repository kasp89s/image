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
                    <div class="profile replies">
                        <div class="profile-head clearfix">
                            <div class="l-profile-head">
                                <h1><?= \Yii::t('app', 'Replies')?></h1>
                            </div>
                        </div>
                        <div class="c-profile">
                            <?php if (!empty($comments)):?>
                                <?php foreach ($comments as $comment):?>
                                    <?php
                                    $image = !empty($comment->post->images[0]->album) ? $comment->post->images[0]->album->cover : $comment->post->images[0]->thumb90;
                                    ?>
                                <div class="comments replies clearfix">
                                    <figure><a href="<?= Url::to('/' . $comment->post->url)?>"><img src="<?= $image?>" alt="" /></a></figure>
                                    <div class="r-comments-galery">
                                        <ul>
                                            <li>
                                                <article>
                                                    <div class="head-comm">
                                                        <p>
                                                            <a href="<?= Url::to('/user/' . $user->username)?>"><?= $user->username?></a>
                                                            <?= User::changeDate($comment->date)?> <a href="javascript:void(0)" class="realy"><?= \Yii::t('app', 'Reply')?></a>
                                                        </p>
                                                    </div>
                                                    <p><?= $comment->message?></p>
                                                </article>
                                                <div class="submit-comment">
                                                    <?= Html::beginForm('/ajax/send-comment-reply', 'post', ['class' => 'comment-reply-form']); ?>
                                                    <?= Html::input('hidden', 'id', $comment->id)?>
                                                    <div class="textr">
                                                        <textarea name="CommentReply[message]" placeholder="<?= \Yii::t('app', 'Submit a comment')?>"></textarea>
                                                        <span class="error-txt"></span>
                                                    </div>
                                                    <input type="submit" value="<?= \Yii::t('app', 'Submit')?>" />
                                                    <?= Html::endForm();?>
                                                </div>
                                                <div class="main-calc">
                                                    <span class="icons minus"></span>
                                                    <input type="text" value="<?= $comment->status?>"/>
                                                    <span class="icons plus"></span>
                                                </div>
                                                <a href="#" class="add-comm open"></a>
                                                <ul style="display: block">
                                                    <?php foreach($comment->replies as $reply):?>
                                                        <?php if($reply->readOwner != 0) continue; ?>

                                                        <?php
                                                        $reply->readOwner = 1;
                                                        $reply->save();
                                                        ?>
                                                    <li>
                                                        <article class="active">
                                                            <div class="head-comm">
                                                                <p>
                                                                    <a href="<?= Url::to('/user/') . $reply->user->username?>">
                                                                        <?= $reply->user->username?></a>
                                                                    <?= User::changeDate($reply->date)?> <a href="javascript:void(0)" class="realy"><?= \Yii::t('app', 'Reply')?></a>
                                                                </p>
                                                            </div>
                                                            <p><?= $reply->message?></p>
                                                        </article>
                                                        <div class="submit-comment">
                                                            <?= Html::beginForm('/ajax/send-comment-reply', 'post', ['class' => 'comment-reply-form']); ?>
                                                            <?= Html::input('hidden', 'id', $comment->id)?>
                                                            <div class="textr">
                                                                <textarea name="CommentReply[message]" placeholder="<?= \Yii::t('app', 'Submit a comment')?>"></textarea>
                                                                <span class="error-txt"></span>
                                                            </div>
                                                            <input type="submit" value="<?= \Yii::t('app', 'Submit')?>" />
                                                            <?= Html::endForm();?>
                                                        </div>
                                                        <div class="main-calc">
                                                            <span class="icons minus"></span>
                                                            <input type="text" value="0"/>
                                                            <span class="icons plus"></span>
                                                        </div>
                                                    </li>
                                                    <?php endforeach;?>
                                                </ul>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                <?php endforeach;?>
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
                <?= $this->render('block/guestProfileMenu', []); ?>
            <?php endif;?>
            <div class="baner-right">
                <a href="#"><img src="images/r5.jpg" alt="" /></a>
            </div>
        </div>
    </div>
</div>
