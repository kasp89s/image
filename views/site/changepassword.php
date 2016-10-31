<?php
use yii\helpers\Html;
?>
    <div class="middle">
        <div class="container">
           <div class="c-content">
				<div class="passw-rec">
					<h1><?= \Yii::t('app', 'Password Recovery')?></h1>
					<div class="passw-form">
						<?= Html::beginForm('', 'post', ['id' => 'changepassword']); ?>
							<p>
								<?= Html::input('password', 'PasswordRecovery[password]', null, ['type' => "password", 'placeholder' => \Yii::t('app', 'enter new password'), 'id' => 'password'])?>
								<span class="error-txt"></span>
							</p> 
							<p>
								<?= Html::input('password', 'PasswordRecovery[password_retype]', null, ['type' => "password", 'placeholder' => \Yii::t('app', 'repeat please'), 'id' => 'password_retype'])?>
								<span class="error-txt"></span>
							</p> 
							<p><input type="submit" value="<?= \Yii::t('app', 'Continue')?>" class="btn" /></p> 
						<?= Html::endForm();?>
					</div>
				</div>
			</div>
        </div>
    </div>