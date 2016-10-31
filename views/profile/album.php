<?php
use yii\helpers\Url;
use yii\helpers\Html;
?>
    <div class="middle">
        <div class="container clearfix">
			<div class="column-left">
				<div class="block-main clearfix">
					<form action="#">
						<div class="reg-sorting-alb">
						<div class="head-main clearfix">
							<h1><?= \Yii::t('app', 'Albums')?></h1>
							<div class="r-head-main">
								<ul>
									<li><a href="#create-new-album" class="inline"><?= \Yii::t('app', 'New album')?></a></li>
									<li style="display: none"><a href="#change-album-modal" class="inline"><?= \Yii::t('app', 'Change')?></a></li>
								</ul>
							</div>
						</div>
                            <ul id="sortable" class="sortable-album">
							<?php foreach ($this->params['user']->albums as $album):?>
								<li class="reg-sorting-image" id="item-<?= $album->id?>"
                                    data-id="<?= $album->id?>"
                                    data-title="<?= $album->title?>"
                                    data-description="<?= $album->description?>"
                                    data-type="<?= $album->type?>">
									<a href="http://<?= $_SERVER['HTTP_HOST'] . Url::to('/a/' . $album->url)?>"><img src="<?= $album->cover?>" alt="<?= $album->title?>" /></a>
									<span class="drag-image"></span>
									<div class="settings-image">
										<a href="javascript:void(0)"></a>
										<ul>
											<li><a href="javascript:void(0)" class="change-album"><?= \Yii::t('app', 'Change settings')?></a></li>
                                            <?php if (!empty($album->imagesCount)):?>
											    <li><a href="<?= Url::to('/profile/change-album/' . $album->id)?>"><?= \Yii::t('app', 'Titles/descriptions')?></a></li>
                                            <?php endif;?>
                                            <?php if (!empty($album->imagesCount)):?>
											    <li><a href="<?= Url::to('/profile/rearrange-album-images/' . $album->id)?>"><?= \Yii::t('app', 'Rearrange images')?></a></li>
                                            <?php endif;?>
											<li><a href="<?= Url::to('/profile/remove-album/' . $album->id)?>"><?= \Yii::t('app', 'Delete album')?></a></li>
											<?php if ($album->active == 0 && !empty($album->imagesCount)):?>
											<li><a href="#form-center-<?= $album->id?>" class="inline"><?= \Yii::t('app', 'Share album')?></a></li>
											<?php endif;?>
										</ul>
									</div>
								</li>
							<?php endforeach;?>
                            </ul>
						</div>
					</form>
				</div>
			</div>
			<?= $this->render('block/rightColumn', []); ?>
        </div>
    </div>
<?= $this->render('block/modals', []); ?>

<?php foreach ($this->params['user']->albums as $album):?>
    <?php if ($album->active == 0 && !empty($album->imagesCount)):?>
<div style="display:none">
<div class="form-center" id="form-center-<?= $album->id?>">
    <h3><?= \Yii::t('app', 'Share with the WebSite community')?></h3>
    <?= Html::beginForm('/ajax/share', 'post', ['class' => 'form-share-album']); ?>
    <?= Html::input('hidden', 'UserAlbum[id]', $album->id)?>
    <div class="line-block">
        <div class="title-form">
            <?= Html::input('text', 'UserPost[title]', null, ['placeholder' => \Yii::t('app', 'Give your post a Title *')])?>
            <span class="error-txt title"></span>
        </div>
        <label class="matyre">
            <?= Html::input('checkbox', 'UserPost[mature]', 1)?> <?= \Yii::t('app', 'mature content')?> (?)
            <span class="toltip"><?= \Yii::t('app', 'Select this if you\'re posting content intended for a mature audience only (sexually explicit content is not allowed on WebSite')?>).</span>
        </label>
    </div>
    <div class="line-block clearfix">
        <div class="select-form">
            <?= Html::input('hidden', 'UserPost[topic]', null, ['id' => 'topic-id'])?>
            <h4><?= \Yii::t('app', 'Pick a Topic')?> *</h4>
            <div class="selected-all">
                <h4><?= \Yii::t('app', 'Pick a topic')?> <i class="icons"></i></h4>
                <div class="c-selected-all">
                    <ul>
                        <?php foreach (\Yii::$app->params['topicValues'] as $key => $value):?>
                            <li>
                                <h4 data-id="<?= $key?>"><?= $value['title']?> <i class="icons"></i></h4>
                                <p><?= $value['description']?></p>
                            </li>
                        <?php endforeach;?>
                    </ul>
                </div>
            </div>
            <span class="error-txt topic"></span>
        </div>

        <div class="add-optional">
            <div class="click-optional">
                <?= Html::input('text', null, null, ['placeholder' => \Yii::t('app', 'add optional tags'), 'id' => 'input-tags'])?>
                <div class="add-tag">
                    <p>
                    </p>
                </div>
            </div>
            <div class="selected-optional">
                <div class="scroll-pane">
                    <h5><?= \Yii::t('app', 'TAG SUGGESTIONS')?></h5>
                    <ul>
                        <!--
                            <li>
                                <h6>funny</h6>
                                <p>3,267,384 images</p>
                            </li>
                            -->
                    </ul>
                </div>
            </div>
        </div>

    </div>
    <ul class="main-section-form ui-sortable">
        <?php if (!empty($album->images)):?>
            <?php foreach ($album->images as $image):?>
                <li class="clearfix" id="item-<?= $image->id?>">
                    <figure><a href="<?= $image->original?>" class="ajax"><img src="<?= $image->thumb160?>" alt="" /></a></figure>
                    <div class="r-section-form">
                        <?= Html::input('hidden', 'image'.$image->id.'[UserImage][id]', $image->id)?>
                        <?= Html::input('text', 'image'.$image->id.'[UserImage][title]', $image->title, ['placeholder' => \Yii::t('app', 'Title')])?>
                        <?= Html::textarea('image'.$image->id.'[UserImage][description]', $image->description, ['placeholder' => \Yii::t('app', 'Description')]) ?>
                    </div>
                    <i class="up"></i>
                </li>
            <?php endforeach;?>
        <?php endif;?>
    </ul>
    <div style="height: 40px;"></div>
    <p class="txt-center" style="bottom: 45px;left: 40%;position: absolute;">
        <?= Html::input('submit', null, \Yii::t('app', 'Finish and Share!'), ['class' => 'btn'])?>
    </p>
    <?= Html::endForm();?>
</div>
</div>
    <?php endif;?>
<?php endforeach;?>
