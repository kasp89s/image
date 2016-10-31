<?php
use yii\helpers\Html;
use app\models\LoginForm;
use yii\helpers\Url;
?>
<div class="middle">
    <div class="container">
        <div class="sign-up in-sign">
            <div class="sign-head">
                <h3><?= \Yii::t('app', 'Login with')?></h3>
            </div>
            <div class="center-sign-up">
                <?= Html::beginForm('login', 'post', ['class' => 'login-form']); ?>
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
                    <div class="c-f-main">
                        <p>
                             <?= Html::input('text', 'LoginForm[username]', null, ['placeholder' => \Yii::t('app', 'Username or Email')])?>
                            <span class="error-txt l-l-e"></span>
                        </p>
                        <p>
                            <?= Html::input('password', 'LoginForm[password]', null, ['type' => "password", 'placeholder' => \Yii::t('app', 'Password')])?>
                            <span class="error-txt l-p-e"></span>
                        </p>
                    </div>
                    <div class="txt-right">
                        <a href="<?= Url::to('/registration')?>" class="link-reg"><?= \Yii::t('app', 'Register')?></a>
                        <input type="submit" class="btn" value="<?= \Yii::t('app', 'Sign In')?>" />
                    </div>
                <?= Html::endForm();?>
            </div>
        </div>
    </div>
</div>