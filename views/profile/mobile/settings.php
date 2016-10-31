<?php
use yii\helpers\Html;
?>
<?= $this->render('//mobile/block/header'); ?>
    <div class="middle">
        <div class="container">
           <div class="setting-main" style="margin-left: 40%">
				<div class="head-main clearfix">
					<h1><?= \Yii::t('app', 'Settings')?></h1>
				</div>				
				<div class="c-setting-main">	
					<?= Html::beginForm('', 'post', ['class' => 'settings-form']); ?>
						<div class="block-setting">
							<h5><?= \Yii::t('app', 'Password and email')?></h5>
							<div class="in-block <?= isset($errors['password']) ? 'error' : ''?>">
								<p><?= \Yii::t('app', 'Current Password *')?></p>
								<?= Html::input('password', 'SettingForm[password]', null, ['id' => 'password'])?>
								<?= Html::error($model,'password', ['class' => 'in-error', 'style' => 'position: static;']);?>
							</div>
							<div class="in-block <?= isset($errors['email']) ? 'error' : ''?>">
								<p><?= \Yii::t('app', 'Email')?></p>
								<?= Html::input('email', 'SettingForm[email]', $this->params['user']->email, ['id' => 'email', 'placeholder' => 'example@google.com'])?>
								<?= Html::error($model,'email', ['class' => 'in-error', 'style' => 'position: static;']);?>
							</div>
							<div class="in-block <?= isset($errors['newPassword']) ? 'error' : ''?>">
								<p><?= \Yii::t('app', 'New Password')?></p>
								<?= Html::input('password', 'SettingForm[newPassword]', null, ['id' => 'newPassword'])?>
								<?= Html::error($model,'newPassword', ['class' => 'in-error', 'style' => 'position: static;']);?>
							</div>
							<div class="in-block <?= isset($errors['newPassword_retype']) ? 'error' : ''?>">
								<p><?= \Yii::t('app', 'Verify New Password')?></p>
								<?= Html::input('password', 'SettingForm[newPassword_retype]', null, ['id' => 'newPassword_retype'])?>
								<?= Html::error($model,'newPassword_retype', ['class' => 'in-error', 'style' => 'position: static;']);?>
							</div>
						</div>
						<div class="block-setting">
							<h5><?= \Yii::t('app', 'Account')?></h5>
							<div class="in-block <?= isset($errors['username']) ? 'error' : ''?>">
								<p><?= \Yii::t('app', 'Username')?>*</p>
								<?= Html::input('text', 'SettingForm[username]', $this->params['user']->username, ['id' => 'email', 'placeholder' => ''])?>
								<?= Html::error($model,'username', ['class' => 'in-error']);?>
							</div>
							<p>
								<label>
									<?= Html::input('checkbox', 'SettingForm[mature]', 1,
									[
									'id' => 'mature',
									!empty($this->params['user']->mature) ? 'checked' : '' => !empty($this->params['user']->mature),
									]
									)?>
									<?= \Yii::t('app', 'Show mature content')?>
								</label>
							</p>
							<div class="preferences">
								<h5><?= \Yii::t('app', 'Email preferences')?></h5>
								<p>
									<label>
										<?= Html::input('checkbox', 'SettingForm[notification]', 1, 
										[
										'id' => 'notification',
										empty($this->params['user']->notification) ? 'checked' : '' => empty($this->params['user']->notification),
										])?>
										<?= \Yii::t('app', 'Dont send me notification emails')?>
									</label>
								</p>
								<p>
									<label>
										<?= Html::input('checkbox', 'SettingForm[promotional]', 1, [
										'id' => 'promotional',
										empty($this->params['user']->promotional) ? 'checked' : '' => empty($this->params['user']->promotional),
										])?>
										<?= \Yii::t('app', 'Dont send me promotional emails')?>
									</label>
								</p>
							</div>
						</div>
						<p>
							<?= Html::input('submit', null, \Yii::t('app', 'Save'), ['class' => 'btn'])?>
						</p>
					<?= Html::endForm();?>
				</div>
			</div>
        </div>
    </div>

<?= $this->render('//mobile/block/main_left'); ?>
