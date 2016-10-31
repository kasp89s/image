<?php 
	use yii\helpers\Html;
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
                        <h3>Uploaded 2 days ago</h3>
                    </div>
					<?php if (!empty($images)):?>
						<?php foreach ($images as $image):?>
						<div class="article-photo">
							<h4><?= $image->title?></h4>
						</div>
						<div class="figure-photo">
                        <div class="c-photo">
                            <a href="/<?= \Yii::$app->params['imageUploadFolder']?>/original/<?= $image->original?>" class="ajax">
								<img src="/<?= \Yii::$app->params['imageUploadFolder']?>/original/<?= $image->original?>" alt="" />
							</a>
                            <div class="pic-link">
                                <div class="share-link">
                                    <a href="javascript:void(0)"></a>
                                    <div class="share-link-on">
                                        <p>Share link</p>
                                        <div class="inp-link">
                                            <a href="javascript:void(0)" class="copy-link"><span>copy</span></a>
                                            <input type="text" value="http://blabla.com/Hd7kjs8" readonly="readonly" />
                                        </div>
                                        <p>Embeded HTML</p>
                                        <div class="inp-link">
                                            <a href="javascript:void(0)" class="copy-link"><span>copy</span></a>
                                            <input type="text" value="http://blabla.com/Hd7kjs8" readonly="readonly" />
                                        </div>
                                        <p>BBCode (Forums)</p>
                                        <div class="inp-link">
                                            <a href="javascript:void(0)" class="copy-link"><span>copy</span></a>
                                            <input type="text" value="http://blabla.com/Hd7kjs8" readonly="readonly" />
                                        </div>
                                    </div>
                                </div>
                                <div class="inp-link r-link">
                                    <a href="javascript:void(0)" class="copy-link"><span>copy</span></a>
                                    <input type="text" value="http://blabla.com/Hd7kjs8" readonly="readonly" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="article-photo">
                        <p><?= $image->description?></p>
                    </div>
                    <br />
						<?php endforeach;?>
					<?php endif;?>
                     <div class="b-photo clearfix">
                        <ul>
                            <li><a href="javascript:void(0)" class="icons star-up"></a></li>
                            <li>
                                <p>2,945,831 views</p>
                            </li>
                        </ul>
                        <div class="r-b-photo">

                        </div>
                    </div>
                </div>
            </div>
            <div class="right-column">
                <div class="block-main">
                    <header>Image Options</header>
                    <div class="c-block-main">
                        <ul class="menu-right">
                            <li><a href="#">Browse all images</a></li>
                            <li><a href="#">Edit image titles or descriptions</a></li>
                            <li><a href="#">Rearrange images</a></li>
                            <li><a href="#">Edit images</a></li>
                            <li><a href="#change-album-poop" class="inline">Change album settings</a></li>
                        </ul>
                        <a href="#form-center" class="share-btn sh inline">Share with the community</a>
                        <ul class="who-album">
                            <li><a href="photo_unreg_rearrange.html" class="del-album"><span>Delete Album</span></a></li>
                            <li><a href="#" class="down-album"><span>Download Images</span></a></li>
                        </ul>
                    </div>
                </div>
                <div class="block-main">
                    <header>Share this album</header>
                    <div class="c-block-main">
                        <p>Share link</p>
                        <div class="inp-link">
                            <a href="javascript:void(0)" class="copy-link"><span>copy</span></a>
                            <input type="text" value="http://blabla.com/Hd7kjs8" readonly="readonly" />
                        </div>
                        <p>Embed in HTML</p>
                        <div class="inp-link">
                            <a href="javascript:void(0)" class="copy-link"><span>copy</span></a>
                            <input type="text" value="http://blabla.com/Hd7kjs8fdfgg" readonly="readonly" />
                        </div>
                    </div>
                </div>
                <div class="baner-right">
                    <a href="#"><img src="images/r4.jpg" alt="" /></a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- MODALS -->
<div class="form-center" id="form-center">
		<h3>Share with the WebSite community</h3>
		<form action="#">
			<div class="line-block">
				<div class="title-form">
					<input type="text" value="" placeholder="Give your post a Title *" />
				</div>
				<label class="matyre">
					<input type="checkbox" value="" name="" /> mature content (?)
					<span class="toltip">Select this if you're posting content intended for a mature audience only (sexually explicit content is not allowed on WebSite).</span>
				</label>
			</div>
			<div class="line-block clearfix">
				<div class="select-form">
					<h4>Pick a Topic *</h4>
					<div class="selected-all">
						<h4>Pick a topic <i class="icons"></i></h4>
						<div class="c-selected-all">
							<ul>
								<li>
									<h4>Funny <i class="icons"></i></h4>
									<p>If it makes you laugh, you’ll find it here.</p>
								</li>
								<li>
									<h4>Funny2 <i class="icons"></i></h4>
									<p>If it makes you laugh, you’ll find</p>
								</li>
								<li>
									<h4>Funny <i class="icons"></i></h4>
									<p>If it makes you laugh, you’ll find</p>
								</li>
								<li>
									<h4>Funny <i class="icons"></i></h4>
									<p>If it makes you laugh, you’ll find it here.</p>
								</li>
								<li>
									<h4>Funny <i class="icons"></i></h4>
									<p>If it makes you laugh, you’ll find it here.</p>
								</li>
								<li>
									<h4>Funny <i class="icons"></i></h4>
									<p>If it makes you laugh, you’ll find</p>
								</li>
							</ul>
						</div>
					</div>
				</div>
				
				<div class="add-optional">
					<div class="click-optional">
						<span>add optional</span>
						<div class="add-tag">
							<span>tags</span>
							<p>
								<span>funny</span>,
								<span>cute</span>
							</p>
						</div>
					</div>
					<div class="selected-optional">
						<div class="scroll-pane">
							<h5>TAG SUGGESTIONS</h5>
							<ul>
								<li>
									<h6>funny</h6>
									<p>3,267,384 images</p>
								</li>
								<li>
									<h6>funny</h6>
									<p>3,267,384 images</p>
								</li>
								<li>
									<h6>funny</h6>
									<p>3,267,384 images</p>
								</li>
								<li>
									<h6>funny</h6>
									<p>3,267,384 images</p>
								</li>
								<li>
									<h6>funny</h6>
									<p>3,267,384 images</p>
								</li>
								<li>
									<h6>funny</h6>
									<p>3,267,384 images</p>
								</li>
							</ul>
						</div>
					</div>
				</div>
				
			</div>
			<ul class="main-section-form">
				<li class="clearfix">
					<figure><a href="images/r2.jpg" class="ajax"><img src="images/r2.jpg" alt="" /></a></figure>
					<div class="r-section-form">
						<input type="text" value="" placeholder="Title" />
						<textarea placeholder="Description"></textarea>
					</div>
					<i class="up"></i>
				</li>
				<li class="clearfix">
					<figure><a href="images/r2.jpg" class="ajax"><img src="images/r2.jpg" alt="" /></a></figure>
					<div class="r-section-form">
						<input type="text" value="" placeholder="Title" />
						<textarea placeholder="Description"></textarea>
					</div>
					<i class="up"></i>
				</li>
			</ul>
			<p class="txt-center" style="bottom: 45px;left: 40%;position: absolute;">>
				<input type="submit" class="btn " value="Finish and Share!" />
			</p>
		</form>
	</div>
	
	<div class="change-album-poop" id="change-album-poop">
	<div class="c-change-album-poop">
		<?= Html::beginForm('site/changealbum', 'post', ['class' => 'form-change-album-poop']); ?>
			<?= Html::input('hidden', 'UserAlbum[id]', $album->id)?>
			<?= Html::input('hidden', 'UserAlbum[cover]', $images[0]->thumb90, ['id' => 'album-cover'])?>
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
							<img src="/<?= \Yii::$app->params['imageUploadFolder']?>/thumb90/<?= $album->cover?>" alt="" />
						<?php else:?>
							<img src="/<?= \Yii::$app->params['imageUploadFolder']?>/thumb90/<?= $images[0]->thumb90?>" alt="" />
						<?php endif;?>
					</figure>
					<input type="submit" value="<?= \Yii::t('app', 'Save')?>" class="btn" />
				</div>
			</div>
			<ul class="change-list">
				<li data-value="Public"><i class="icons"></i><?= \Yii::t('app', 'Public')?></li>
				<li class="active" data-value="Hidden"><i class="icons"></i><?= \Yii::t('app', 'Hidden')?></li>
				<li data-value="Secret"><i class="icons"></i><?= \Yii::t('app', 'Secret')?></li>
			</ul>
			<div class="box">
				<p><?= \Yii::t('app', 'Anyone can see this album by going to your account page located at')?>: </p>
				<p><a href="#">http://scorpion434.website.com</a></p>
			</div>
			<div class="box visible">
				<p><?= \Yii::t('app', 'Anyone can see this album by going')?>: </p>
				<p><a href="#">http://scorpion434.website.com</a></p>
			</div>
			<div class="box">
				<p><?= \Yii::t('app', 'Anyone page located at')?>: </p>
				<p><a href="#">http://scorpion434.website.com</a></p>
			</div>
			<div class="b-change-album">
				<h3><?= \Yii::t('app', 'Select a new cover for this album')?></h3>
				<div class="coveralbum">
					<div class="scroll-pane">
						<ul>
						<?php if (!empty($images)):?>
							<?php foreach ($images as $image):?>
								<li><figure><img src="/<?= \Yii::$app->params['imageUploadFolder']?>/thumb90/<?= $image->thumb90?>" data-image="<?= $image->thumb90?>" alt="" onclick="$('#album-cover').val($(this).data('image'))" /></figure></li>
							<?php endforeach;?>
						<?php endif;?>
						</ul>
					</div>
				</div>
			</div>
		<?= Html::endForm();?>
	</div>
</div>
