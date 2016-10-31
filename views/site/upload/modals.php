<?php
use yii\helpers\Html;
use yii\helpers\Url;
use app\models\UserImage;
?>
<!-- MODALS -->
<div style="display: none;">
<div class="form-center" id="form-center">
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
								<h4 data-id="<?= $key?>"><?= \Yii::t('app', $value['title'])?> <i class="icons"></i></h4>
								<p><?= \Yii::t('app', $value['description'])?></p>
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
        <ul class="main-section-form">
		<?php if (!empty($album->images)):?>
            <?php foreach ($album->images as $image):?>
            <li class="clearfix">
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
        <p class="txt-center" style="bottom: 45px;left: 40%;position: absolute;">
			<?= Html::input('submit', null, \Yii::t('app', 'Finish and Share!'), ['class' => 'btn'])?>
        </p>
    <?= Html::endForm();?>
</div>
</div>

<div class="change-album-poop" id="change-album-poop">
    <div class="c-change-album-poop">
        <?= Html::beginForm('/site/changealbum', 'post', ['class' => 'form-change-album-poop']); ?>
        <?= Html::input('hidden', 'UserAlbum[id]', $album->id)?>
        <?= Html::input('hidden', 'UserAlbum[cover]', $album->images[0]->thumb90, ['id' => 'album-cover'])?>
        <?= Html::input('hidden', 'UserAlbum[type]', 'Hidden', ['id' => 'album-type'])?>
        <div class="head-upload-image">
            <h2><?= \Yii::t('app', 'Change Album settings')?></h2>
        </div>
        <div class="t-change-album clearfix">
            <div class="l-change-album">
                <?= Html::input('text', 'UserAlbum[title]', $album->title, ['placeholder' => \Yii::t('app', 'Album Title (optional)')])?>
                <?= Html::textarea('UserAlbum[description]', $album->description, ['placeholder' => \Yii::t('app', 'Album Description (optional)')]) ?>
            </div>
            <div class="r-change-album">
                <p><?= \Yii::t('app', 'Album Cover')?></p>
                <figure>
                    <?php if (!empty($album->cover)):?>
                        <img src="<?= $album->cover?>" alt="" />
                    <?php else:?>
                        <img src="<?= $album->images[0]->thumb90?>" alt="" />
                    <?php endif;?>
                </figure>
                <input type="submit" value="<?= \Yii::t('app', 'Save')?>" class="btn" />
            </div>
        </div>
        <div class="radio-sorting" style="margin-left: 28px">
            <label><input type="radio" value="Public" name="UserAlbum[type]" <?= ($album->type == 'Public') ? 'checked="checked"' : ''?>/> <?= \Yii::t('app', 'Public')?></label>
            <label><input type="radio" value="Hidden" name="UserAlbum[type]" <?= ($album->type == 'Hidden') ? 'checked="checked"' : ''?>/> <?= \Yii::t('app', 'Hidden')?></label>
            <label><input type="radio" value="Secret" name="UserAlbum[type]" <?= ($album->type == 'Secret') ? 'checked="checked"' : ''?>/> <?= \Yii::t('app', 'Secret')?></label>
        </div>
        <div class="box Public <?= ($album->type == 'Public') ? 'visible' : ''?>" style="<?= ($album->type == 'Public') ? 'display: block;' : 'display: none;'?>">
            <p><?= \Yii::t('app', 'Anyone can see this album by going to your account page located at')?>: </p>
            <p><a href="http://<?= $_SERVER['HTTP_HOST']?>/a/<?= $album->url?>">http://<?= $_SERVER['HTTP_HOST']?>/a/<?= $album->url?></a></p>
        </div>
        <div class="box Hidden <?= ($album->type == 'Hidden') ? 'visible' : ''?>" style="<?= ($album->type == 'Hidden') ? 'display: block;' : 'display: none;'?>">
            <p><?= \Yii::t('app', 'Anyone can see this album by going')?>: </p>
            <p><a href="http://<?= $_SERVER['HTTP_HOST']?>/a/<?= $album->url?>">http://<?= $_SERVER['HTTP_HOST']?>/a/<?= $album->url?></a></p>
        </div>
        <div class="box Secret <?= ($album->type == 'Secret') ? 'visible' : ''?>" style="<?= ($album->type == 'Secret') ? 'display: block;' : 'display: none;'?>">
            <p><?= \Yii::t('app', 'Anyone page located at')?>: </p>
            <p><a href="http://<?= $_SERVER['HTTP_HOST']?>/a/<?= $album->url?>">http://<?= $_SERVER['HTTP_HOST']?>/a/<?= $album->url?></a></p>
        </div>
        <div class="b-change-album">
            <h3><?= \Yii::t('app', 'Select a new cover for this album')?></h3>
            <div class="coveralbum">
                <div class="scroll-pane">
                    <ul>
                        <?php if (!empty($album->images)):?>
                            <?php foreach ($album->images as $image):?>
                                <li><figure><img src="<?= $image->thumb90?>" data-image="<?= $image->thumb90?>" alt="<?= $image->title?>" onclick="$('#album-cover').val($(this).data('image'))" /></figure></li>
                            <?php endforeach;?>
                        <?php endif;?>
                    </ul>
                </div>
            </div>
        </div>
        <?= Html::endForm();?>
    </div>
</div>

<div class="poop-editor">
	<span class="bg-bd"></span>
	<div class="poop-image-editor">
		<div class="sign-head">
			<h3><?= \Yii::t('app', 'Image editor')?></h3>
			<span class="off-editor"></span>
		</div>
			<div class="editor-image">
                <div class="connected-carousels">
                    <div class="navigation nav-editor">
                        <span class="prev"></span>
                        <span class="next"></span>
                        <div class="carousel carousel-navigation">
                            <?php if (!empty($album->images)):?>
                                <ul>
                                    <?php foreach ($album->images as $key => $image):?>
                                        <li>
                                            <img src="<?= $image->thumb90?>" alt="<?= $image->title?>" data-original="<?= $image->original?>" />
                                        </li>
                                    <?php endforeach;?>
                                </ul>
                            <?php endif;?>
                        </div>
                        <div class="nav-editor-top">
                            <ul class="nav-ul">
                                <li class="active"><i class="icon-main res-ed"></i></li>
                                <li><i class="icon-main rot-ed"></i></li>
                                <li><i class="icon-main rt-ed"></i></li>
                            </ul>
                            <i class="es-edit active" style="cursor: pointer;" data-method="getCroppedCanvas"></i>
                        </div>
                        <div class="nav-editor-bot" style="display: none;">
                            <input type="text" id="width" readonly=""/>
                            <span class="mult">x</span>
                            <input type="text" id="height" readonly=""/>
                        </div>
                    </div>

                    <div class="stage image-editor-box crop visible">
                        <div class="carousel carousel-stage">
                            <?php if (!empty($album->images)):?>
                            <ul>
                                <?php foreach ($album->images as $key => $image):?>
                                    <li style="width: 998px">
                                            <img src="<?= $image->original?>" alt="<?= $image->title?>" width="100%"/>
                                    </li>
                                <?php endforeach;?>
                            </ul>
                            <?php endif;?>
                        </div>
                        <a href="#" class="prev prev-stage" style="display: none"><span>&lsaquo;</span></a>
                        <a href="#" class="next next-stage" style="display: none"><span>&rsaquo;</span></a>
                    </div>
                    <div class="image-editor-box rotate">
                        <div class="rotate-image">
                            <ul class="clearfix">
                                <li class="active" style="width: 499px; height: 334px;"><img src="<?= $image->original?>" data-rotate="0"/></li>
                                <li style="width: 499px; height: 334px;"><img src="<?= $image->original?>" data-rotate="90" /></li>
                                <li style="width: 499px; height: 334px;"><img src="<?= $image->original?>" data-rotate="180" /></li>
                                <li style="width: 499px; height: 334px;"><img src="<?= $image->original?>" data-rotate="270" /></li>
                            </ul>
                        </div>
                    </div>
                </div>
			</div>
	</div>
</div>

<div style="display:none">
    <div class="get-deletion-link" id="changes-saved">
        <p><?= \Yii::t('app', 'All changes saved')?>.</p>
        <p><a href="javascript:void(0)" class="btn" onclick="$('#cboxClose').trigger('click')"><?= \Yii::t('app', 'Okay')?></a></p>
    </div>
</div>

<a href="#changes-saved" class="click-changes-saved inline" style="display: none;">!</a>
