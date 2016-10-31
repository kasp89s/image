<?php
use yii\helpers\Html;
use app\models\LoginForm;
use yii\helpers\Url;
use \yii\captcha\Captcha;
?>
<?= $this->render('//mobile/block/header'); ?>
<div class="middle">
    <div class="container">
        <div class="sign-up in-sign" id="sign-in">
            <div class="sign-head">
                <h3><?= \Yii::t('app', 'Register with')?></h3>
            </div>
            <div class="center-sign-up">
                <?= Html::beginForm('register', 'post', ['class' => 'register-form']); ?>
                <?= Html::input('hidden', 'RegisterForm[captcha]', null, ['id' => 'captcha-value'])?>
                    <div class="social-reg">
                        <ul>
                            <li><a href="<?= $this->params['facebook']->getRedirectLoginHelper()->getLoginUrl('http://' . Yii::$app->getRequest()->serverName . '/social/facebook', ['email'])?>" class="icon-cosial ff"></a></li>
                            <li><a href="#" class="icon-cosial tvt"></a></li>
                            <li><a href="<?= $this->params['yahoo']->getOpenIDUrl($this->params['yahoo']->callback_url);?>" class="icon-cosial yah"></a></li>
                            <li><a href="<?= $this->params['vk']->getLoginUrl()?>" class="icon-cosial vk"></a></li>
                            <li><a href="<?= $this->params['weibo']->getAuthorizeURL('http://' . Yii::$app->getRequest()->serverName . '/social/weibo')?>" class="icon-cosial sl"></a></li>
                            <li><a href="<?= $this->params['google']->createAuthUrl()?>" class="icon-cosial gl"></a></li>
                        </ul>
                    </div>
                    <p class="txt-center"><?= \Yii::t('app', 'or')?></p>
                    <div class="reg-main-top">
                        <div class="c-f-main">
                            <p>
                                <?= Html::input('text', 'RegisterForm[username]', null, ['placeholder' => \Yii::t('app', 'Username'), 'id' => 'username'])?>
                                <span class="error-txt"></span>
                            </p>
                            <p>
                                <?= Html::input('email', 'RegisterForm[email]', null, ['placeholder' => \Yii::t('app', 'Email'), 'id' => 'email'])?>
                                <span class="error-txt"></span>
                            </p>
                            <p>
                                <?= Html::input('password', 'RegisterForm[password]', null, ['type' => "password", 'placeholder' => \Yii::t('app', 'Password'), 'id' => 'password'])?>
                                <span class="error-txt"></span>
                            </p>
                            <p>
                                <?= Html::input('password', 'RegisterForm[password_retype]', null, ['type' => "password", 'placeholder' => \Yii::t('app', 'Retype Password'), 'id' => 'password_retype'])?>
                                <span class="error-txt"></span>
                            </p>
                            <p class="by-reg"><?= \Yii::t('app', 'By registering you agree to our')?> <a href="/rules"><?= \Yii::t('app', 'terms of service')?></a></p>
                        </div>
                        <div class="txt-right">
                            <a href="<?= Url::to('/auth')?>" class="link-reg"><?= \Yii::t('app', 'sign In')?></a>
                            <?= Html::button(\Yii::t('app', 'Next'), ['class' => 'btn', 'id' => 'check-register']) ?>
                        </div>
                    </div>
                <?= Html::endForm();?>
                    <div class="reg-main-es">
                        <ul>
                            <?php echo Captcha::widget([
                                'name' => 'RegisterForm[captcha]',
                                'template' => '<li class="active">{image}</li> <li>{input}</li><span class="error-txt"></span>',
                                'options' => ['placeholder' => \Yii::t('app', 'Type the text'), 'id' => 'captcha-input']
                            ]);?>
                        </ul>
                        <div class="txt-right">
                            <a href="javascript:void(0)" class="link-reg go-back"><?= \Yii::t('app', 'back')?></a>
                            <?= Html::button(\Yii::t('app', 'Register'), ['class' => 'btn', 'id' => 'register-button']) ?>
                        </div>
                    </div>
            </div>
        </div>
    </div>
</div>
<?= $this->render('//mobile/block/main_left'); ?>