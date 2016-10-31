<div class="right-column">	
				<div class="block-main">
					<header><?= \Yii::t('app', 'Upload Images')?></header>
					<div class="upload-right">
						<form action="#">
							<div class="t-upload clearfix">
								<div class="type_file">
									<input type="file" class="inputFile" id="inputFileProfile" onchange="profileUpload('inputFileProfile');" multiple="" />
									<input type="text" class="inputFileVal" value="Computer" />
								</div>
								<div class="image-url">
									<label><input type="text" placeholder="<?= \Yii::t('app', 'enter image URLs')?>" onchange="toDataUrlProfile(this.value)" /></label>
								</div>
							</div>
							<div class="c-upload-right">
								<p>or <span class="green"><?= \Yii::t('app', 'drag and drop')?></span> <?= \Yii::t('app', 'images onto this page')?></p>
								<p>or <span class="green">Ctrl + V</span> <?= \Yii::t('app', 'to paste from your clipboard')?></p>
								<div class="image-sorting" id="s3">
									<a href="javascript:void(0)">
										<h4 data-id="0"><?= \Yii::t('app', 'Add to album')?></h4>
									</a>
									<div class="all-images-select">
										<div class="scroll-pane">
											<ul>
                                                <li class="clearfix">
                                                        <h6><?= \Yii::t('app', 'Non album images')?></h6>
                                                </li>
											<?php foreach ($this->params['user']->albums as $album):?>
												<li class="clearfix">
													<figure><img src="<?= $album->cover?>" alt="<?= $album->title?>" /></figure>
													<div class="txt-image">
														<h6 data-id="<?= $album->id?>"><?= $album->title?></h6>
												</li>
											<? endforeach;?>
											</ul>
										</div>
									</div>
								</div>
								<select name="resize">
									<option value="auto"><?= \Yii::t('app', 'Auto resize')?></option>
									<option value="80x80">80x80 (<?= \Yii::t('app', 'avatar')?>)</option>
									<option value="150x112">150x112 (<?= \Yii::t('app', 'thumbnail')?>)</option>
									<option value="320x240">320x240 (<?= \Yii::t('app', 'fthumbnail')?>)</option>
									<option value="640x480">640x480 (<?= \Yii::t('app', 'forums, message boards')?>)</option>
									<option value="800x600">800x600 (<?= \Yii::t('app', '15-inch monitor')?>)</option>
									<option value="1024x768">1024x768 (<?= \Yii::t('app', '17-inch monitor')?>)</option>
									<option value="1280x1024">1280x1024 (<?= \Yii::t('app', '19-inch monitor')?>)</option>
									<option value="1600x1200">1600x1200 (<?= \Yii::t('app', '20-inch monitor')?>)</option>
								</select>
								<div class="load-image-main">
								</div>
<!--								<div class="load-image-main error">-->
<!--									<p>image-with-red-bordr.png</p>-->
									<span class="load-all" style="display: none;"><i style="width:0%" data-percent="0"></i></span>
<!--								</div>-->
							</div>
						</form>
					</div>
				</div>
				
			</div>
