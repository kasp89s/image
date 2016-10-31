<?php
use yii\helpers\Url;
?>
<div class="main-left">
    <div class="logo">
        <a href="/"><img src="/images/logo.png" alt="" /></a>
    </div>
    <div class="search">
        <form action="/search">
            <input type="text" value="" name="request" placeholder="<?= \Yii::t('app', 'Search images')?>" />
        </form>
    </div>
    <div class="menu-left">
        <ul>
            <li><a href="/"><?= \Yii::t('app', 'Home')?></a></li>
            <?php if (\Yii::$app->user->isGuest): ?>
                <li><a href="<?= Url::to('/auth')?>"><?= \Yii::t('app', 'Sign in')?></a></li>
                <li><a href="<?= Url::to('/registration')?>"><?= \Yii::t('app', 'Register')?></a></li>
            <?php endif;?>
        </ul>
        <?php if (!\Yii::$app->user->isGuest): ?>
        <ul>
            <li><a href="<?= Url::to('/profile/image')?>"><?= \Yii::t('app', 'Images')?></a></li>
            <li><a href="<?= Url::to('/profile/album')?>"><?= \Yii::t('app', 'Albums')?></a></li>
            <li><a href="<?= Url::to('/favorites')?>"><?= \Yii::t('app', 'Favorites')?></a></li>
            <li><a href="<?= Url::to('/user/') . $this->params['user']->username?>"><?= \Yii::t('app', 'Profile')?></a></li>
            <li><a href="<?= Url::to('/profile/settings')?>"><?= \Yii::t('app', 'Settings')?></a></li>
            <li><a href="<?= Url::toRoute('logout')?>"><?= \Yii::t('app', 'Sign out')?></a></li>
        </ul>
        <?php endif;?>
    </div>
    <div class="bot-main">
        <ul>
            <?php foreach(\Yii::$app->params['languages'] as $key => $language):?>
            <li <?php if (\Yii::$app->language == $key):?>class="active"<?php endif;?>>
                <a href="?<?= http_build_query(array_merge($_GET, ['l' => $key]))?>"><?= $language['name']?></a>
            </li>
            <?php endforeach;?>
        </ul>
        <p><a href="?version=full"><?= \Yii::t('app', 'View Desktop Site')?></a></p>
    </div>
    <i class="of-main"></i>
</div>
