<?php
use yii\helpers\Html;
use yii\helpers\Url;
use app\models\UserImage;
?>
<div style="display:none">
    <div class="get-deletion-link" id="get-deletion-link">
        <h3><?= \Yii::t('app', 'Get deletion link')?></h3>
        <form action="#">
            <div class="inp-link">
                <a href="javascript:void(0)" data-clipboard-target="#deletion-link" class="copy-link"><span><?= \Yii::t('app', 'copy')?></span></a>
                <input id="deletion-link" type="text" value="http://<?= Yii::$app->getRequest()->serverName?>/r/<?= $image->url?>" readonly="readonly" />
            </div>
            <p class="txt-center"><input type="submit" class="btn bg-gray" value="<?= \Yii::t('app', 'Okay')?>" /></p>
        </form>
    </div>
</div>

<div style="display: none;">
<div class="form-center" id="form-center">
    <h3><?= \Yii::t('app', 'Share with the WebSite community')?></h3>
    <?= Html::beginForm('/ajax/share', 'post', ['class' => 'form-share-album']); ?>
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
    <ul class="main-section-form">
        <?php if (!empty($images)):?>
            <?php foreach ($images as $image):?>
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
                            <ul>
                                    <li>
                                        <img src="<?= $image->thumb90?>" alt="<?= $image->title?>" data-original="<?= $image->original?>" />
                                    </li>
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
                                    <li style="width: 998px">
                                        <img src="<?= $image->original?>" alt="<?= $image->title?>" width="100%"/>
                                    </li>
                            </ul>
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
