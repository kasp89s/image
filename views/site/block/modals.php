<?php 
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use app\models\LoginForm;
use app\models\RegisterForm;
use \yii\captcha\Captcha;
?>
<div style="display:none">
    <div class="load-image" id="load-poop" style="height: 135px;">
        <div class="head-upload-image">
            <h3><?= \Yii::t('app', 'Upload images')?></h3>
            <a href="/" class="user-image"><?= (!empty($this->params['user'])) ? $this->params['user']->username : ''?></a>
        </div>
        <span class="load-all"><i style="width:0%" data-percent="0"></i></span>
        <ul class="upload-images">
        </ul>
    </div>
</div>

<div style="display:none">
    <div class="sign-up" id="sign-up">
        <div class="sign-head">
            <h3><?= \Yii::t('app', 'Sign Up')?></h3>
            <p><?= \Yii::t('app', 'We are happy to see you)')?></p>
        </div>
        <div class="center-sign-up">
            <?= Html::beginForm('register', 'post', ['class' => 'register-form']); ?>
				<?= Html::input('hidden', 'RegisterForm[captcha]', null, ['id' => 'captcha-value'])?>
                <div class="social-reg">
                    <ul>
                        <li><a href="<?= $this->params['facebook']->getRedirectLoginHelper()->getLoginUrl('http://' . Yii::$app->getRequest()->serverName . '/social/facebook', ['email'])?>" class="icon-social ff"></a></li>
                        <li><a href="javascript:void(0)" class="icon-social tvt"></a></li>
                        <!--                        <li><a href="--><?//= $this->params['yahoo']->getAuthorizationUrl($this->params['yahooToken']);?><!--" class="icon-social yah"></a></li>-->
                        <li><a href="<?= $this->params['yahoo']->getOpenIDUrl($this->params['yahoo']->callback_url);?>" class="icon-social yah"></a></li>
                        <li><a href="<?= $this->params['vk']->getLoginUrl()?>" class="icon-social vk"></a></li>
                        <li><a href="<?= $this->params['weibo']->getAuthorizeURL('http://' . Yii::$app->getRequest()->serverName . '/social/weibo')?>" class="icon-social sl"></a></li>
                        <li><a href="<?= $this->params['google']->createAuthUrl()?>" class="icon-social gl"></a></li>
                    </ul>
                </div>
                <p class="txt-center"><?= \Yii::t('app', 'or')?></p>
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
                <div class="b-sign-up clearfix">
					<?= Html::button(\Yii::t('app', 'Sign Up'), ['class' => 'btn', 'id' => 'check-register']) ?>
                    <a href="#sign-up-es" class="btn inline go-captcha" style="display: none;"><?= \Yii::t('app', 'Sign Up')?></a>
                    <?= \Yii::t('app', 'By registering you agree to our')?>
                    <span><a href="/rules"><?= \Yii::t('app', 'terms of service')?></a></span>
                </div>
                <div class="already-reg">
                    <div class="c-already-reg clearfix">
                        <a href="#sign-in" class="btn-sign inline"><?= \Yii::t('app', 'Sign In')?></a>
                        <h3><?= \Yii::t('app', 'Already registered')?>?</h3>
                        <p><?= \Yii::t('app', 'Welcome back')?>!</p>
                    </div>
                </div>
			<?= Html::endForm();?>
        </div>
    </div>
</div>

<div style="display:none">
    <div class="sign-up es" id="sign-up-es">
        <div class="sign-head">
            <h3><?= \Yii::t('app', 'Sign Up');?></h3>
            <p><?= \Yii::t('app', 'We are happy to see you)')?></p>
        </div>
		<form>
            <div class="txt-center">
						 <?php echo Captcha::widget([
                            'name' => 'RegisterForm[captcha]',
                            'template' => '<div class="digit-main">{image}</div> <p>{input}<span class="error-txt"></span></p>',
                            'options' => ['placeholder' => \Yii::t('app', 'Type the text'), 'id' => 'captcha-input']
                            ]);?>
                <p>
				<?= Html::button(\Yii::t('app', 'Sign Up'), ['class' => 'btn', 'id' => 'register-button']) ?>
				</p>
            </div>
		</form>
    </div>
</div>

<div style="display:none">
    <div class="sign-up in-sign" id="sign-in">
        <div class="sign-head">
            <h3><?= \Yii::t('app', 'Already registered?')?></h3>
            <p><?= \Yii::t('app', 'Welcome back!')?></p>
        </div>
        <div class="center-sign-up">
			<?= Html::beginForm('login', 'post', ['class' => 'login-form']); ?>
                <div class="social-reg">
                    <ul>
                        <li><a href="<?= $this->params['facebook']->getRedirectLoginHelper()->getLoginUrl('http://' . Yii::$app->getRequest()->serverName . '/social/facebook', ['email'])?>" class="icon-social ff"></a></li>
                        <li><a href="javascript:void(0)" class="icon-social tvt"></a></li>
<!--                        <li><a href="--><?//= $this->params['yahoo']->getAuthorizationUrl($this->params['yahooToken']);?><!--" class="icon-social yah"></a></li>-->
                        <li><a href="<?= $this->params['yahoo']->getOpenIDUrl($this->params['yahoo']->callback_url);?>" class="icon-social yah"></a></li>
                        <li><a href="<?= $this->params['vk']->getLoginUrl()?>" class="icon-social vk"></a></li>
                        <li><a href="<?= $this->params['weibo']->getAuthorizeURL('http://' . Yii::$app->getRequest()->serverName . '/social/weibo')?>" class="icon-social sl"></a></li>
                        <li><a href="<?= $this->params['google']->createAuthUrl()?>" class="icon-social gl"></a></li>
                    </ul>
                </div>
                <p class="txt-center">or</p>
                <p>
					<?= Html::input('text', 'LoginForm[username]', null, ['placeholder' => \Yii::t('app', 'Username or Email')])?>
                    <span class="error-txt l-l-e"></span>
                </p>
                <p>
					<?= Html::input('password', 'LoginForm[password]', null, ['type' => "password", 'placeholder' => \Yii::t('app', 'Password')])?>
                    <span class="error-txt l-p-e"></span>
                    <a href="/recovery" class="torgot"><?= \Yii::t('app', 'forgot')?>?</a>
                </p>
                <div class="txt-right">
                    <a href="#sign-up" class="link-reg inline"><?= \Yii::t('app', 'Register')?></a>
                    <input type="submit" class="btn" value="<?= \Yii::t('app', 'Sign In')?>" />
                </div>
             <?= Html::endForm();?>
        </div>
    </div>
</div>
