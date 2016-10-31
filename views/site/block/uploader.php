<div class="upload-image" id="poop">
    <div class="head-upload-image">
        <h3><?= \Yii::t('app', 'Upload images')?></h3>
        <a href="#" class="user-image"><?= (!empty($this->params['user'])) ? $this->params['user']->username : ''?></a>
    </div>
    <form action="#">
        <div class="t-upload clearfix">
            <div class="type_file">
                <input type="file" class="inputFile" id="inputFile" onchange="fileUpload('inputFile');" multiple="">
                <input type="text" class="inputFileVal" value="<?= \Yii::t('app', 'browse your computer')?>" />
            </div>
            <div class="image-url">
                <label><input type="text" placeholder="<?= \Yii::t('app', 'enter image URLs')?>" onchange="toDataUrl(this.value)"/></label>
            </div>
            <div class="gr-add-dr">
                <p><?= \Yii::t('app', 'drag and drop here')?></p>
            </div>
        </div>

        <div class="es-pfoto" style="position: fixed; top: -9999px;">
            <div class="error-image" style="display:none;">
                <p><?= \Yii::t('app', 'Upload fail!!!')?></p>
                <p><?= \Yii::t('app', 'What’s a fuck is going on?')?></p>
            </div>
            <div class="upload-album" style="display:none;">
                <p class="txt-no"><?= \Yii::t('app', 'We are ready for upload')?>
				<span class="album-ready" style="display:none;"><?= \Yii::t('app', 'new album with')?></span>
				<span class="upload-image-count">0</span>
				<?= \Yii::t('app', 'images')?>.</p>
                <p class="txt-es"><?= \Yii::t('app', 'What’s a fuck is going on?')?>
				<?= \Yii::t('app', 'Upload fail!!!')?> <?= \Yii::t('app', 'What’s a fuck is going on?')?> <?= \Yii::t('app', 'Upload fail!!!')?></p>
            </div>
            <div class="head-list">
                <ul>
                    <li>
						<!-- <input type="submit" value="publish to Site" /> -->
						<a href="javascript:void(0)" class="publish <?= (\Yii::$app->user->isGuest) ? 'blocked' : ''?>"><?= \Yii::t('app', 'publish to Site')?></a>
					</li>
                    <li><a href="javascript:void(0)" class="create"><?= \Yii::t('app', 'create album')?></a></li>
                    <li><a href="#load-poop" class="start-upload inline blocked"><?= \Yii::t('app', 'Start Upload!')?></a></li>
                </ul>
            </div>
            <div class="title-album">
                <input type="text" value="" placeholder="<?= \Yii::t('app', 'Optional Album Title')?>" />
            </div>
            <div class="main-image">
                <div class="scroll-pane" style="width: 500px;">
                    <ul class="upload-image-list">

                    </ul>
                </div>
            </div>
        </div>
    </form>
</div>
