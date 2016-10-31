<?php
use yii\helpers\Url;
use app\models\User;
use app\components\CommentWidget;
?>
    <div class="middle">
        <div class="container">
			<div class="social-left">
                <ul>
                    <li><a href="https://www.facebook.com/sharer/sharer.php?sdk=joey&u=<?= Yii::$app->getRequest()->serverName?>/<?= $post->url?>" class="icon-social ff"
                           onclick="window.open(this.href, 'mywin', 'left=20,top=20,width=600,height=500,toolbar=1,resizable=0'); return false;"></a></li>
                    <li><a href="https://twitter.com/home?status=<?= Yii::$app->getRequest()->serverName?>/<?= $post->url?>" class="icon-social tvt"
                           onclick="window.open(this.href, 'mywin', 'left=20,top=20,width=600,height=500,toolbar=1,resizable=0'); return false;"></a></li>
                    <!--                    <li><a href="#" class="icon-social yah"></a></li>-->
                    <li><a href="http://vk.com/share.php?url=http://<?= Yii::$app->getRequest()->serverName?>/<?= $post->url?>" class="icon-social vk"
                           onclick="window.open(this.href, 'mywin', 'left=20,top=20,width=600,height=500,toolbar=1,resizable=0'); return false;"></a></li>
                    <li><a href="http://service.weibo.com/share/share.php?url=http://<?= Yii::$app->getRequest()->serverName?>/<?= $post->url?>&language=<?= \Yii::$app->language?>" class="icon-social sl"
                           onclick="window.open(this.href, 'mywin', 'left=20,top=20,width=600,height=500,toolbar=1,resizable=0'); return false;"></a></li>
                    <li><a href="https://plus.google.com/share?url=<?= Yii::$app->getRequest()->serverName?>/<?= $post->url?>" class="icon-social gl"
                           onclick="window.open(this.href, 'mywin', 'left=20,top=20,width=600,height=500,toolbar=1,resizable=0'); return false;"></a></li>
                </ul>
            </div>
            <div class="photo clearfix">
				<div class="column-left">
					<div class="block-main">
						<div class="head-photo clearfix">
							<div class="l-head-photo">
								<h1><?= $post->title ?></h1>
								<p>
									<?= \Yii::t('app', 'Uploaded')?>
									<?= \Yii::t('app', 'by')?>
									<a href="<?= Url::to('/user/') . $post->user->username?>"><?= $post->user->username?></a>
									<?= User::changeDate($post->date)?>
								</p>
							</div>
							<div class="r-head-photo">
								<?php if (!empty($prevPost)):?>
								<a href="<?= Url::to('/' . $prevPost->url)?>" class="btn-prev"></a>
								<?php endif;?>
								<?php if (!empty($rightBarPosts[0])):?>
								<a href="<?= Url::to('/' . $rightBarPosts[0]->url)?>" class="btn"><?= \Yii::t('app', 'Next Post')?></a>
								<?php endif;?>
							</div>
						</div>
                        <?php foreach ($post->images as $image):?>
                        <?php if (!empty($image->title)):?>
                            <div class="article-photo">
                                <h4><?= $image->title?></h4>
                            </div>
                        <?php endif;?>
						<div class="figure-photo">
							<div class="c-photo">
								<a href="<?= $image->original?>" class="ajax"><img src="<?= $image->original?>" alt="<?= $image->title?>" /></a>
							</div>
						</div>
                        <?php if (!empty($image->description)):?>
                            <div class="article-photo">
                                <h2><?= $image->description?></h2>
                            </div>
                        <?php endif;?>
							<br />
                        <?php endforeach;?>
						<div class="b-photo clearfix">
							<ul>
							<?php if(!\Yii::$app->user->isGuest):?>
								<li><a href="javascript:void(0)" class="icons top-up <?= (isset($_SESSION['likePost'][$post->id]) && $_SESSION['likePost'][$post->id] == 'inc') ? 'active' : ''?>" onclick="like('<?= $post->id?>', 'inc')"></a></li>
								<li><a href="javascript:void(0)" class="icons bot-up <?= (isset($_SESSION['likePost'][$post->id]) && $_SESSION['likePost'][$post->id] == 'dec') ? 'active' : ''?>" onclick="like('<?= $post->id?>', 'dec')"></a></li>
								<li><a href="javascript:void(0)" class="icons star-up <?= (in_array($post->id, $_SESSION['favorites'])) ? 'active' : ''?>" onclick="favorite('<?= $post->id?>', this)"></a></li>
							<?php endif ?>
								<li>
									<p><span class="like-image-count"><?= number_format($post->likeCount)?></span> <?= \Yii::t('app', 'likes')?></p>
									<p><span class="view-image-count"><?= number_format($post->viewCount)?></span> <?= \Yii::t('app', 'views')?></p>
								</li>
							</ul>
							<div class="r-b-photo">
								<p>
								<?php if (!empty($post->tags)):?>
								<span><?= \Yii::t('app', 'Tags')?>:</span>
									<?php foreach($post->tags as $key => $tag):?>
									<?php if ($key + 1 != count($post->tags)):?>
									<a href="/search?tag=<?= trim($tag->title)?>"><?= trim($tag->title)?></a>,
									<?php else:?>
									<a href="/search?tag=<?= trim($tag->title)?>"><?= trim($tag->title)?></a>
									<?php endif;?>
									<?php endforeach;?>
								<?php endif;?>
								</p>
							</div>
						</div>
					</div>
					<?= CommentWidget::widget(['model' => $post]) ?>
				</div>
				<?php if (count($post->user->posts) > 1):?>
				<div class="right-column">
					<div class="block-image-more">
						<h3><?= \Yii::t('app', 'More Images')?></h3>
						<div class="scroll-pane">
							<ul>
								<li class="active">
									<a href="javascript:void(0)" class="clearfix">
										<figure><img src="<?= $image->thumb90?>" alt="<?= $image->title?>" /></figure>
										<div class="txt-block">
											<p><?= $post->title?></p> 
										</div>
										<div class="bot-image-more">
											<span class="like-im"><?= number_format($post->likeCount)?></span>
											<span class="com-im"><?= number_format($post->commentCount)?></span>
										</div>
									</a>
								</li>
								<?php foreach ($rightBarPosts as $p):?>
<!--								--><?php //if ($p->id == $post->id) continue; ?>
                                <?php
                                    $image = isset($p->images[0]->album->cover) ? $p->images[0]->album->cover : $p->images[0]->thumb90;
                                ?>
								<li>
									<a href="<?= Url::to('/' . $p->url)?>" class="clearfix">
										<figure><img src="<?= $image?>" alt="<?= $p->title?>" /></figure>
										<div class="txt-block">
											<p><?= $p->title?></p> 
										</div>
										<div class="bot-image-more">
											<span class="like-im"><?= number_format($p->likeCount)?></span>
											<span class="com-im"><?= number_format($p->commentCount)?></span>
										</div>
									</a>
								</li>
								<?php endforeach;?>
							</ul>
						</div>
					</div>
					<div class="baner-right">
						<a href="#"><img src="images/r5.jpg" alt="" /></a>
					</div>
				</div>
				<?php endif;?>
			</div>
        </div>
    </div>
