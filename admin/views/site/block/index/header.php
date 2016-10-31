<?php
use yii\helpers\Html;
?>
    <header class="header clearfix" role="banner">
		<div class="logo">
			<a href="#">
				<img src="/web/images/logo.png" alt="" />
				<span>партнерская программа</span>
			</a>
		</div>
		<div class="form-top">
			<?= Html::beginForm('login'); ?>
				<?= Html::input('text', 'LoginForm[username]', null, ['placeholder' => 'Введите логин (email)'])?>
				<?= Html::input('password', 'LoginForm[password]', null, ['placeholder' => 'Введите пароль'])?>
				<?= Html::input('submit', null, 'Войти')?>
				<?= Html::a('забыли пароль?', 'restore', $options = [] )?>
			<?= Html::endForm();?>
		</div>
		<span class="line lines"></span>
    </header><!-- /header-->