<?php
use yii\helpers\Html;
?>
    <div class="middle">
        <div class="container">
           <div class="c-content">
				<div class="passw-rec">
					<h1><?= \Yii::t('app', 'Password Recovery')?></h1>
					<p><?= \Yii::t('app', 'If you forgot your password then we\'ll attempt to email you instructions on how to reset it.')?>
					<?= \Yii::t('app', 'If you don\'t receive the email then try looking in your spam folder or allowing all emails from Imgur.com')?>.</p>
					<div class="passw-form">
						<?= Html::beginForm('recovery', 'post'); ?>
						<p style="display: block; position: relative;">
							<?= Html::input('text', 'PasswordRecovery[username]', null, ['placeholder' => \Yii::t('app', 'Username or email address'), 'id' => 'username'])?>
							<span class="error-txt"></span>
						</p> 
						<p><input type="submit" value="Continue" class="btn" /></p> 
						<?= Html::endForm();?>
					</div>
				</div>
			</div>
        </div>
    </div>
