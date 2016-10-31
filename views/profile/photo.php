<?php
    use yii\helpers\Html;

    use app\assets\EditorAsset;
    EditorAsset::register($this);
?>
<div class="middle">
    <div class="container clearfix">
        <div class="column-left">
            <div class="block-main clearfix">
                    <div class="head-main clearfix">
                        <h1><?= $countImages ?> <?= \Yii::t('app', 'images') ?></h1>
                    </div>
                    <div class="sorting-album">
                        <?= Html::beginForm('', 'post', ['class' => 'sorting-form']); ?>
                        <?= Html::input('hidden', 'albumId', $selectedId, ['id' => 'album-id'])?>
                        <div class="image-sorting" id="s1">
                            <a href="javascript:void(0)">
                                <h4 data-id="<?= $selectedId ?>"><?= $selectedOption ?></h4>
                            </a>

                            <div class="all-images-select">
                                <div class="scroll-pane">
                                    <ul>
                                        <li class="clearfix">
                                            <div class="txt-image">
                                                <h6 data-id="0"><?= \Yii::t('app', 'Non album images') ?></h6>
                                            </div>
                                        </li>
                                        <?php if (!empty($this->params['user']->albums)): ?>
                                            <?php foreach ($this->params['user']->albums as $album): ?>
                                                <li class="clearfix">
                                                    <figure><img src="<?= $album->cover ?>" alt="<?= $album->title ?>"/>
                                                    </figure>
                                                    <div class="txt-image">
                                                        <h6 data-id="<?= $album->id ?>"><?= $album->title ?></h6>
                                                    </div>
                                                </li>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <select name="order_value" class="order-select">
                            <option value="time"><?= \Yii::t('app', 'Time uploaded') ?></option>
                            <option value="name"><?= \Yii::t('app', 'Original filename') ?></option>
                        </select>
                        <select name="order_type" class="order-select">
                            <option value="asc"><?= \Yii::t('app', 'Ascending') ?></option>
                            <option value="desc"><?= \Yii::t('app', 'Descending') ?></option>
                        </select>
                        <?= Html::endForm();?>
                    </div>
                    <div class="sorting-album work-space kick-out">
                        <?php if (!empty($this->params['user']->albums)): ?>
                            <div class="image-sorting" id="s2">
                                <a href="javascript:void(0)">
                                    <h4 data-id="0"><?= \Yii::t('app', 'Move to album') ?></h4>
                                </a>

                                <div class="all-images-select">
                                    <div class="scroll-pane">
                                        <ul>
                                            <?php foreach ($this->params['user']->albums as $album): ?>
                                                <li class="clearfix">
                                                    <figure><img src="<?= $album->cover ?>" alt="<?= $album->title ?>"/>
                                                    </figure>
                                                    <div class="txt-image">
                                                        <h6 data-id="<?= $album->id ?>"><?= $album->title ?></h6>
                                                    </div>
                                                </li>
                                            <?php endforeach; ?>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                        <ul class="links-sorting">
                            <li><a href="javascript:void(0)" class="image-link"><?= \Yii::t('app', 'Get Links') ?></a>
                            </li>
                            <?php if (empty($startImages[0]->post)):?>
                            <li><a href="javascript:void(0)" class="image-remove"><?= \Yii::t('app', 'Delete') ?></a>
                            </li>
                            <li><a href="javascript:void(0)" class="edit-image" onclick="reloadScripts()"><?= \Yii::t('app', 'Edit') ?></a></li>
                            <?php endif;?>
                        </ul>
                    </div>
                    <div style="display: none;" class="sorting-album link-space">
                        <div class="main-form-sorting">
                            <div class="radio-sorting">
                                <ul>
                                    <li class="active"><i></i> <?= \Yii::t('app', 'Link') ?></li>
                                    <li><i></i> <?= \Yii::t('app', 'Direct Link') ?></li>
                                    <li><i></i> <?= \Yii::t('app', 'HTML Image') ?></li>
                                    <li><i></i> <?= \Yii::t('app', 'BBCode') ?></li>
                                    <li><i></i> <?= \Yii::t('app', 'Linked BBCode') ?></li>
                                </ul>
                            </div>
                            <div class="box-texter visible">

                                    <textarea class="link link-span" id="copy-link"></textarea>

                                <p class="txt-center">
                                    <button type="button" data-clipboard-target="#copy-link"
                                            class="btn copy-link-btn"><?= \Yii::t('app', 'Copy & close') ?></button>
                                </p>
                            </div>
                            <div class="box-texter">

                                    <textarea class="direct link-span" id="copy-direct"></textarea>

                                <p class="txt-center">
                                    <button type="button" data-clipboard-target="#copy-direct"
                                            class="btn copy-link-btn"><?= \Yii::t('app', 'Copy & close') ?></button>
                                </p>
                            </div>
                            <div class="box-texter">

                                    <textarea class="html-image link-span" id="copy-html-image"></textarea>

                                <p class="txt-center">
                                    <button type="button" data-clipboard-target="#copy-html-image"
                                            class="btn copy-link-btn"><?= \Yii::t('app', 'Copy & close') ?></button>
                                </p>
                            </div>
                            <div class="box-texter">

                                    <textarea class="bbcode link-span" id="copy-bbcode"></textarea>

                                <p class="txt-center">
                                    <button type="button" data-clipboard-target="#copy-bbcode"
                                            class="btn copy-link-btn"><?= \Yii::t('app', 'Copy & close') ?></button>
                                </p>
                            </div>
                            <div class="box-texter">

                                    <textarea class="linked-bbcode link-span" id="copy-linked-bbcode"></textarea>

                                <p class="txt-center">
                                    <button type="button" data-clipboard-target="#copy-linked-bbcode"
                                            class="btn copy-link-btn"><?= \Yii::t('app', 'Copy & close') ?></button>
                                </p>
                            </div>

                        </div>
                    </div>
                    <div style="display: none;" class="sorting-album remove-elements">
                        <div class="are-you">
                            <p><?= \Yii::t('app', 'Are you sure to delete') ?> <span
                                    class="elements-count">0</span> <?= \Yii::t('app', 'element(s)') ?>?</p>
                            <a href="javascript:void(0)" class="btn red-bg apply-remove"><?= \Yii::t(
                                    'app',
                                    'Yes'
                                ) ?></a>
                            <a href="javascript:void(0)" onclick="$('.remove-elements').hide();"
                               class="btn"><?= \Yii::t('app', 'No') ?></a>
                        </div>
                    </div>
                    <div class="album-main-photo">
                        <?php if (!empty($startImages)): ?>
                            <?php foreach ($startImages as $key => $image): ?>
                                <figure data-id="<?= $image->id ?>" data-href="<?= $image->original ?>"
                                        data-url="http://qruto.com/i/<?= $image->url ?>">
                                    <a href="#poop-image-title" class="inline" onclick="loadCheckedImage(<?= $key?>)">
                                        <img src="<?= $image->thumb90 ?>" alt="<?= $image->title ?>"/>
                                    </a>
                                    <i class="icons"></i>
                                </figure>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>

            </div>
        </div>
        <?= $this->render('block/rightColumn', []); ?>
    </div>
</div>

<?= $this->render('block/modals', ['startImages' => $startImages]); ?>
<div style="display:none">
<div class="form-center slider-share-modal" id="form-center">
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
                    </ul>
                </div>
            </div>
        </div>

    </div>
    <ul class="main-section-form">
    </ul>
    <p class="txt-center" style="bottom: 45px;left: 40%;position: absolute;">
        <?= Html::input('submit', null, \Yii::t('app', 'Finish and Share!'), ['class' => 'btn'])?>
    </p>
    <?= Html::endForm();?>
</div>
</div>
