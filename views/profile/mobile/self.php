<?php
use yii\helpers\Html;
use yii\helpers\Url;
use app\models\User;
use app\models\UserComment;
unset($_GET['username']);
?>
<?= $this->render('//mobile/block/header'); ?>
<div class="middle">
	<div class="container">
		<div class="profile-user">
			<div class="top-profile-user">
				<h1><?= $this->params['user']->username?></h1>
				<p class="green"><?= \Yii::t('app', 'Likes')?>: <?= number_format($this->params['user']->likeCount)?></p>
				<p><?= \Yii::t('app', 'Member since')?>: <?= date('Y', strtotime($this->params['user']->date))?></p>
			</div>
			<div class="view-info">
				<a href="javascript:void(0)"><?= \Yii::t('app', 'Hide info')?></a>
				<div class="all-view-info">
					<p><?= $this->params['user']->about?></p>
				</div>
			</div>
			<select id="select-block">
				<option value="gc"><?= \Yii::t('app', 'Gallery Comments')?></option>
				<option value="gallery-favorites"><?= \Yii::t('app', 'Gallery Favorites')?></option>
				<option value="fav-posts"><?= \Yii::t('app', 'Gallery Posts')?></option>
				<option value="gr"><?= \Yii::t('app', 'Gallery Replies')?></option>
			</select>

			<!-- galery-replies -->
			<?php if (!empty($user->comments)):?>
			<div class="galery-replies gc g-visual-block">
				<ul>
				<?php foreach ($user->comments as $comment):?>
					<?php
					$image = !empty($comment->post->images[0]->album) ? $comment->post->images[0]->album->cover : $comment->post->images[0]->thumb90;
					?>
					<li>
						<figure><img src="<?= $image?>" alt="" /></figure>
						<div class="txt-galery-replies">
							<h4><?= $comment->message?></h4>
							<p>0 <?= \Yii::t('app', 'likes')?>, <?= User::changeDate($comment->date)?></p>
						</div>
					</li>
				<?php endforeach;?>
				</ul>
			</div>
			<?php endif;?>
			<!-- galery-favorites -->
			<?php if (!empty($user->posts)):?>
				<?php $posts = $user->posts;?>
			<div style="display: none;" class="gallery-favorites g-visual-block">
				<?php foreach ($posts as $post):?>
					<?php
					if (empty($post->images)) continue;
					$image = !empty($post->images[0]->album) ? $post->images[0]->album->cover : $post->images[0]->thumb160;
					?>
				<div class="figure">
					<div class="c-figure">
						<a href="<?= Url::to('/' . $post->url)?>">
							<img src="<?= $image?>" alt="<?= $post->title?>" />
						</a>
						<div class="over">
							<p><?= $post->title?></p>
							<span class="view"><?= number_format($post->viewCount)?></span>
						</div>
					</div>
				</div>
				<?php endforeach;?>
			</div>
			<?php endif;?>

			<!-- galery-posts -->
			<?php if (!empty($user->favoritesDesc)):?>
			<div style="display: none;" class="gallery-favorites fav-posts g-visual-block">
				<?php $posts = $user->favoritesDesc;?>
				<?php foreach ($posts as $post):?>
					<?php
					$image = !empty($post->images[0]->album) ? $post->images[0]->album->cover : $post->images[0]->thumb160;
					?>
				<div class="figure">
					<div class="c-figure">
						<a href="<?= Url::to('/' . $post->url)?>">
							<img src="<?= $image?>" alt="<?= $post->title?>" />
						</a>
						<div class="over">
							<p><?= $post->title?></p>
							<div>
								<span class="view"><?= number_format($post->viewCount)?></span>
								<span class="laik-view"><?= number_format($post->likeCount)?></span>
							</div>
						</div>
					</div>
				</div>
				<?php endforeach;?>

			</div>
			<?php endif;?>
			<!-- gallery-comments -->
			<?php
			$comments = UserComment::find()
				->joinWith('replies')
				->andWhere('user_comment.userId = :userId', [':userId' => $this->params['user']->id])
				->andWhere('comment_reply.readOwner = :readOwner', [':readOwner' => 0])
				->orderBy(['user_comment.date' => SORT_DESC])
				->all();
			?>
			<?php if (!empty($comments)):?>

			<div style="display: none;" class="galery-replies gr g-visual-block">
				<ul>
				<?php foreach ($comments as $comment):?>
					<?php
					$image = !empty($comment->post->images[0]->album) ? $comment->post->images[0]->album->cover : $comment->post->images[0]->thumb90;
					?>
					<?php foreach($comment->replies as $reply):?>
						<?php if($reply->readOwner != 0) continue; ?>

						<?php
						$reply->readOwner = 1;
						$reply->save();
						?>
							<li>
								<figure><img src="<?= $image?>" alt="" /></figure>
								<div class="txt-galery-replies">
									<h4><?= $reply->message?></h4>
									<p>0 <?= \Yii::t('app', 'likes')?>, <?= User::changeDate($reply->date)?></p>
								</div>
								<a href="javascript:void(0)" class="off-comments"></a>
							</li>
					<?php endforeach;?>
				<?php endforeach;?>
				</ul>
			</div>
			<?php endif;?>
		</div>
	</div>
</div>

<?= $this->render('//mobile/block/main_left'); ?>