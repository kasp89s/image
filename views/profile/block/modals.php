<?php
use yii\helpers\Html;
?>
<div style="display:none">
	<div class="create-new-album" id="create-new-album">
		<div class="sign-head">
			<h3><?= \Yii::t('app', 'Create new album')?></h3>
		</div>
        <?= Html::beginForm('/ajax/new-album', 'post', ['class' => 'form-new-album']); ?>
			<p>
                <?php $newAlbumUrl = \app\models\UserAlbum::generateUrl();?>
                <?= Html::input('hidden', 'UserAlbum[url]', $newAlbumUrl, [])?>
                <?= Html::input('text', 'UserAlbum[title]', null, ['placeholder' => \Yii::t('app', 'Album Title (optional)')])?>
                <span class="error-txt title" style="bottom: auto; margin-top: -20px;"></span>
			</p>
            <?= Html::textarea('UserAlbum[description]', null, ['placeholder' => \Yii::t('app', 'Album Description (optional)')]) ?>
			<input type="submit" value="<?= \Yii::t('app', 'Save')?>" class="btn" />
			<div class="radio-sorting">
				<label><input type="radio" value="Public" name="UserAlbum[type]" checked="checked"/> <?= \Yii::t('app', 'Public')?></label>
				<label><input type="radio" value="Hidden" name="UserAlbum[type]" /> <?= \Yii::t('app', 'Hidden')?></label>
				<label><input type="radio" value="Secret" name="UserAlbum[type]" /> <?= \Yii::t('app', 'Secret')?></label>
			</div>
            <div class="box Public visible" style="display: block;">
                <p><?= \Yii::t('app', 'Anyone can see this album by going to your account page located at')?>: </p>
                <p><a href="http://<?= $_SERVER['HTTP_HOST']?>/a/<?= $newAlbumUrl?>">http://<?= $_SERVER['HTTP_HOST']?>/a/<?= $newAlbumUrl?></a></p>
            </div>
            <div class="box Hidden" style="display: none;">
                <p><?= \Yii::t('app', 'Anyone can see this album by going')?>: </p>
                <p><a href="http://<?= $_SERVER['HTTP_HOST']?>/a/<?= $newAlbumUrl?>">http://<?= $_SERVER['HTTP_HOST']?>/a/<?= $newAlbumUrl?></a></p>
            </div>
            <div class="box Secret" style="display: none;">
                <p><?= \Yii::t('app', 'Anyone page located at')?>: </p>
                <p><a href="http://<?= $_SERVER['HTTP_HOST']?>/a/<?= $newAlbumUrl?>">http://<?= $_SERVER['HTTP_HOST']?>/a/<?= $newAlbumUrl?></a></p>
            </div>
        <?= Html::endForm();?>
	</div>
</div>
<div style="display:none">
	<div class="create-new-album" id="change-album-modal">
		<div class="sign-head">
			<h3><?= \Yii::t('app', 'Change album')?></h3>
		</div>
        <?= Html::beginForm('/ajax/change-album', 'post', ['class' => 'form-change-album']); ?>
			<p>
                <?= Html::input('hidden', 'UserAlbum[id]', null, ['id' => 'change-album-id'])?>
                <?= Html::input('text', 'UserAlbum[title]', null, ['placeholder' => \Yii::t('app', 'Album Title (optional)')])?>
                <span class="error-txt title" style="bottom: auto; margin-top: -20px;"></span>
			</p>
            <?= Html::textarea('UserAlbum[description]', null, ['placeholder' => \Yii::t('app', 'Album Description (optional)')]) ?>
			<input type="submit" value="<?= \Yii::t('app', 'Save')?>" class="btn" />
			<div class="radio-sorting">
				<label><input type="radio" value="Public" name="UserAlbum[type]" /> <?= \Yii::t('app', 'Public')?></label>
				<label><input type="radio" value="Hidden" name="UserAlbum[type]" /> <?= \Yii::t('app', 'Hidden')?></label>
				<label><input type="radio" value="Secret" name="UserAlbum[type]" /> <?= \Yii::t('app', 'Secret')?></label>
			</div>
        <div class="box Public visible" style="display: block;">
            <p><?= \Yii::t('app', 'Anyone can see this album by going to your account page located at')?>: </p>
            <p><a href="" class="album-type-link"></a></p>
        </div>
        <div class="box Hidden" style="display: none;">
            <p><?= \Yii::t('app', 'Anyone can see this album by going')?>: </p>
            <p><a href="" class="album-type-link"></a></p>
        </div>
        <div class="box Secret" style="display: none;">
            <p><?= \Yii::t('app', 'Anyone page located at')?>: </p>
            <p><a href="" class="album-type-link"></a></p>
        </div>
        <?= Html::endForm();?>
	</div>
</div>

<div style="display:none">
    <div class="poop-image-title clearfix" id="poop-image-title">
        <div class="head-poop-image">
            <ul>
                <li class="active"><?= \Yii::t('app', 'Image')?></li>
                <li><?= \Yii::t('app', 'Title/Description')?></li>
            </ul>
        </div>
        <script type="text/javascript">
            <?php if (!empty($startImages)):?>
                var SliderImages = {
                    <?php foreach ($startImages as $image):?>
                    '<?= $image->original?>': {
                        id: '<?= $image->id?>',
                        thumb160: '<?= $image->thumb160?>',
                        title: '<?= $image->title?>',
                        description: '<?= $image->description?>',
                        name: '<?= $image->name?>',
                        views: '<?= $image->views?>',
                        submited: '<?= app\models\User::changeDate($image->date)?>',
                        links: {
                            share: 'http://<?= \Yii::$app->getRequest()->serverName?>/i/<?= $image->url?>',
                            direct: '<?= $image->original?>',
                            html: '<img src="<?= $image->original?>" /> ',
                            bbcode: '[img]<?= $image->original?>[/img] '
                        },
                        share: {
                            ff: 'https://www.facebook.com/sharer/sharer.php?sdk=joey&u=<?= Yii::$app->getRequest()->serverName?>/i/<?= $image->url?>',
                            tvt: 'https://twitter.com/home?status=<?= Yii::$app->getRequest()->serverName?>/i/<?= $image->url?>',
                            vk: 'http://vk.com/share.php?url=http://<?= Yii::$app->getRequest()->serverName?>/i/<?= $image->url?>',
                            sl: 'http://service.weibo.com/share/share.php?url=http://<?= Yii::$app->getRequest()->serverName?>/i/<?= $image->url?>&language=<?= \Yii::$app->language?>',
                            gl: 'https://plus.google.com/share?url=<?= Yii::$app->getRequest()->serverName?>/i/<?= $image->url?>'
                        },
                        active: '<?= $image->active?>'
                    },
                    <?php endforeach;?>
                    'end': {}
                };
            <?php endif;?>
        </script>
        <div class="l-poop-image">
            <?php if (!empty($startImages)):?>
            <div class="box visible">
                <div id="galleria">
                <?php foreach ($startImages as $image):?>
                    <a href="<?= $image->original?>">
                        <img src="<?= $image->thumb90?>" alt="" data-id="<?= $image->id?>" />
                    </a>
                <?php endforeach;?>
                </div>
            </div>
            <?php endif;?>
            <div class="box">
                <div class="box-form">
                    <form action="/site/changeimage" class="slider-change-image-form">
                        <input type="hidden" name="image[UserImage][id]" />
                        <input type="text" placeholder="<?= \Yii::t('app', 'Image Title (optional)')?>" name="image[UserImage][title]" />
                        <textarea placeholder="<?= \Yii::t('app', 'Image Description (optional)')?>" name="image[UserImage][description]"></textarea>
                        <p class="txt-center"><input type="submit" value="Save" class="btn bg-gray" /></p>
                    </form>
                </div>
            </div>
        </div>
        <div class="r-poop-image">
                <a href="#form-center" class="btn share-slider-button inline"><?= \Yii::t('app', 'Share with the community')?></a>
            <p><?= \Yii::t('app', 'Share link')?></p>
            <div class="inp-link">
                <a href="javascript:void(0)" data-clipboard-target="#share-url-slider" class="copy-link"><span><?= \Yii::t('app', 'copy')?></span></a>
                <input type="text" id="share-url-slider" value="" readonly="readonly" />
            </div>
            <p><?= \Yii::t('app', 'Direct link')?></p>
            <div class="inp-link">
                <a href="javascript:void(0)" data-clipboard-target="#direct-url-slider" class="copy-link"><span><?= \Yii::t('app', 'copy')?></span></a>
                <input type="text" id="direct-url-slider" value="" readonly="readonly" />
            </div>
            <p><?= \Yii::t('app', 'HTML')?></p>
            <div class="inp-link">
                <a href="javascript:void(0)" data-clipboard-target="#html-url-slider" class="copy-link"><span><?= \Yii::t('app', 'copy')?></span></a>
                <input type="text" id="html-url-slider" value="" readonly="readonly" />
            </div>
            <p><?= \Yii::t('app', 'BBCode (Forums, Blogs)')?></p>
            <div class="inp-link">
                <a href="javascript:void(0)" data-clipboard-target="#bbcode-url-slider" class="copy-link"><span><?= \Yii::t('app', 'copy')?></span></a>
                <input type="text" id="bbcode-url-slider" value="" readonly="readonly" />
            </div>
            <div class="deskript-image">
                <p><?= \Yii::t('app', 'Name')?>: <span id="image-slider-name"></span></p>
                <p><?= \Yii::t('app', 'Submited')?>: <span id="image-slider-date"></span></p>
                <p><?= \Yii::t('app', 'Views')?>: <span id="image-slider-views"></span></p>
                <p>&nbsp;</p>
                <p>&nbsp;</p>
            </div>
        </div>
		<div class="social-left slider-social">
            <ul>
                <li><a href="" class="icon-social ff" onclick="window.open(this.href, 'mywin', 'left=20,top=20,width=600,height=500,toolbar=1,resizable=0'); return false;"></a></li>
                <li><a href="" class="icon-social tvt" onclick="window.open(this.href, 'mywin', 'left=20,top=20,width=600,height=500,toolbar=1,resizable=0'); return false;"></a></li>
                <li><a href="" class="icon-social sl" onclick="window.open(this.href, 'mywin', 'left=20,top=20,width=600,height=500,toolbar=1,resizable=0'); return false;"></a></li>
                <li><a href="" class="icon-social vk" onclick="window.open(this.href, 'mywin', 'left=20,top=20,width=600,height=500,toolbar=1,resizable=0'); return false;"></a></li>
                <li><a href="" class="icon-social gl" onclick="window.open(this.href, 'mywin', 'left=20,top=20,width=600,height=500,toolbar=1,resizable=0'); return false;"></a></li>
            </ul>
        </div>
    </div>
</div>

<div style="display:none">
    <div class="get-deletion-link" id="cannot-move">
        <p><?= \Yii::t('app', 'You can not move image')?>.</p>
        <p><a href="javascript:void(0)" class="btn" onclick="$('#cboxClose').trigger('click')"><?= \Yii::t('app', 'Okay')?></a></p>
    </div>
</div>

<div style="display:none">
    <div class="get-deletion-link" id="changes-saved">
        <p><?= \Yii::t('app', 'All changes saved')?>.</p>
        <p><a href="javascript:void(0)" class="btn" onclick="$('#cboxClose').trigger('click')"><?= \Yii::t('app', 'Okay')?></a></p>
    </div>
</div>

<div class="poop-editor">
    <span class="bg-bd"></span>
    <div class="poop-image-editor">
        <div class="sign-head">
            <h3><?= \Yii::t('app', 'Image editor')?></h3>
            <span class="off-editor" onclick="location.reload();"></span>
        </div>
        <div class="editor-image">
            <div class="connected-carousels">
                <div class="navigation nav-editor">
                    <span class="prev"></span>
                    <span class="next"></span>
                    <div class="carousel carousel-navigation">
                            <ul>
                            </ul>
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
                            <ul>
                            </ul>
                    </div>
                    <a href="#" class="prev prev-stage" style="display: none"><span>&lsaquo;</span></a>
                    <a href="#" class="next next-stage" style="display: none"><span>&rsaquo;</span></a>
                </div>
                <div class="image-editor-box rotate">
                    <div class="rotate-image">
                        <ul class="clearfix">
                            <li class="active" style="width: 499px; height: 334px;"><img src="<?//= $image->original?>" data-rotate="0"/></li>
                            <li style="width: 499px; height: 334px;"><img src="<?//= $image->original?>" data-rotate="90" /></li>
                            <li style="width: 499px; height: 334px;"><img src="<?//= $image->original?>" data-rotate="180" /></li>
                            <li style="width: 499px; height: 334px;"><img src="<?//= $image->original?>" data-rotate="270" /></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<a href="#changes-saved" class="click-changes-saved inline" style="display: none;">!</a>
