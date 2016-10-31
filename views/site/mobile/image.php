<?php
use yii\helpers\Url;
use app\models\User;
use app\components\CommentWidget;
?>
<?= $this->render('//mobile/block/post_header', ['nextPostLink' => !empty($rightBarPosts[0]->url) ? $rightBarPosts[0]->url : null]); ?>
<div class="middle">
	<div class="container">
		<div class="photo mh">
			<div class="head-photo clearfix">
				<h1><?= $post->title ?></h1>
				<p><?= \Yii::t('app', 'by')?> <a href="<?= Url::to('/user/') . $post->user->username?>"><?= $post->user->username?></a></p>
				<p><?= User::changeDate($post->date)?></p>
			</div>
			<?php foreach ($post->images as $image):?>
				<div class="figure-photo">
					<div class="c-photo">
						<a href="<?= $image->original?>"><img src="<?= $image->original?>" alt="<?= $image->title?>" /></a>
					</div>
				</div>
			<?php endforeach;?>

			<div class="b-photo clearfix">
				<ul>
					<?php if(!\Yii::$app->user->isGuest):?>
					<li><a href="javascript:void(0)" class="top-up <?= (isset($_SESSION['likePost'][$post->id]) && $_SESSION['likePost'][$post->id] == 'inc') ? 'active' : ''?>" onclick="like('<?= $post->id?>', 'inc')"></a></li>
					<li><a href="javascript:void(0)" class="icons bot-up <?= (isset($_SESSION['likePost'][$post->id]) && $_SESSION['likePost'][$post->id] == 'dec') ? 'active' : ''?>" onclick="like('<?= $post->id?>', 'dec')"></a></li>
					<li><a href="javascript:void(0)" class="icons laik-up <?= (in_array($post->id, $_SESSION['favorites'])) ? 'active' : ''?>" onclick="favorite('<?= $post->id?>', this)"></a></li>
					<?php endif;?>
					<li>
						<p><?= number_format($post->likeCount)?> <?= \Yii::t('app', 'Views')?></p>
						<p><?= number_format($post->viewCount)?> <?= \Yii::t('app', 'Likes')?></p>
					</li>
				</ul>
			</div>
			<?= CommentWidget::widget(['model' => $post]) ?>
		</div>
	</div>
</div>

<?= $this->render('//mobile/block/main_left'); ?>
