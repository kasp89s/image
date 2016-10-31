<?php 
	use yii\helpers\Html;
    use yii\helpers\Url;
    use app\models\User;
    $images = $album->images;

    $this->registerJsFile('/web/js/jquery.jcarousel.min.js',  ['position' => yii\web\View::POS_END]);
    $this->registerJsFile('/web/js/cropper.min.js',  ['position' => yii\web\View::POS_END]);
    $this->registerJsFile('/web/js/imageEditor.js',  ['position' => yii\web\View::POS_END]);
?>
<div class="middle">
    <div class="container">
         <div class="social-left">
                <ul>
                    <li><a href="#" class="icon-social ff"></a></li>
                    <li><a href="#" class="icon-social tvt"></a></li>
                    <li><a href="#" class="icon-social yah"></a></li>
                    <li><a href="#" class="icon-social vk"></a></li>
                    <li><a href="#" class="icon-social sl"></a></li>
                    <li><a href="#" class="icon-social gl"></a></li>
                </ul>
            </div>
        <div class="photo clearfix">
            <div class="column-left">
                <div class="block-main">
                    <div class="head-main clearfix">
                        <h3>
                            <?= \Yii::t('app', 'Uploaded')?>
                            <?php if (!\Yii::$app->user->isGuest):?>
                            <?= \Yii::t('app', 'by')?>
                            <a href="<?= Url::to('/user/') . $album->user->username?>"><?= $album->user->username?>
                                <?php endif;?>
                                <?= User::changeDate($album->date)?>
                        </h3>
                    </div>
					<?php if (!empty($images)):?>
						<?php foreach ($images as $image):?>
                        <?php if (!empty($image->title)):?>
                            <div class="article-photo">
                                <h4><?= $image->title?></h4>
                            </div>
                        <?php endif;?>
						<div class="figure-photo">
                        <div class="c-photo">
                            <a href="<?= $image->original?>" class="ajax">
								<img src="<?= $image->original?>" alt="" />
							</a>
                            <div class="pic-link">
                                <div class="share-link">
                                    <a href="javascript:void(0)"></a>
                                    <div class="share-link-on">
                                        <p><?= \Yii::t('app', 'Share link')?></p>
                                        <div class="inp-link">
                                            <a href="javascript:void(0)" data-clipboard-target="#share-url" class="copy-link"><span><?= \Yii::t('app', 'copy')?></span></a>
                                            <input id="share-url" type="text" value="http://<?= Yii::$app->getRequest()->serverName?>/<?= $image->url?>" readonly="readonly" />
                                        </div>
                                        <p><?= \Yii::t('app', 'Embeded HTML')?></p>
                                        <div class="inp-link">
                                            <a href="javascript:void(0)" data-clipboard-target="#embeded-url" class="copy-link"><span><?= \Yii::t('app', 'copy')?></span></a>
                                            <input id="embeded-url" type="text" value='<img src="<?= $image->original?>" alt="<?= $image->title?>" />' readonly="readonly" />
                                        </div>
                                        <p><?= \Yii::t('app', 'BBCode (Forums)')?></p>
                                        <div class="inp-link">
                                            <a href="javascript:void(0)" data-clipboard-target="#bb-url" class="copy-link"><span><?= \Yii::t('app', 'copy')?></span></a>
                                            <input id="bb-url" type="text" value="[img]<?= $image->original?>[/img]" readonly="readonly" />
                                        </div>
                                    </div>
                                </div>
                                <div class="inp-link r-link">
                                    <a href="javascript:void(0)" data-clipboard-target="#image-url" class="copy-link"><span><?= \Yii::t('app', 'copy')?></span></a>
                                    <input id="image-url" type="text" value="http://<?= Yii::$app->getRequest()->serverName?>/<?= $image->url?>" readonly="readonly" />
                                </div>
                            </div>
                        </div>
                    </div>
                        <?php if (!empty($image->description)):?>
                            <div class="article-photo">
                                <p><?= $image->description?></p>
                            </div>
                        <?php endif;?>
                    <br />
						<?php endforeach;?>
					<?php endif;?>
                     <div class="b-photo clearfix">
                        <div class="r-b-photo">

                        </div>
                    </div>
                </div>
            </div>
            <?= $this->render('rightColumn', ['album' => $album]); ?>
        </div>
    </div>
</div>

<?= $this->render('modals', ['album' => $album]); ?>
