<?php
use yii\helpers\Url;
?>
<header class="header" role="banner">
    <div class="container clearfix">
        <div class="l-header">
            <div class="logo">
                <a href="/"><img src="/images/logo.png" alt="" /></a>
            </div>
            <div class="btn-image <?= (Yii::$app->controller->id == 'site' && Yii::$app->controller->action->id == 'index') ? '' : 'no'?>">
                <a href="#poop" class="up-image inline"><?= \Yii::t('app', 'upload images')?></a>
                <a href="javascript:void(0)" class="all-click"></a>
                <ul>
                    <li><a href="#poop" class="inline"><?= \Yii::t('app', 'Upload images')?></a></li>
                    <li><a href="<?= Url::to('/site/construction')?>"><?= \Yii::t('app', 'Video to GIF')?></a></li>
                </ul>
            </div>
            <div class="lang">
                <a href="javascript:void(0)">
                    <img src="<?= \Yii::$app->params['languages'][\Yii::$app->language]['image']?>" alt="" /> <?= \Yii::$app->params['languages'][\Yii::$app->language]['name']?>
                </a>
                <ul>
                    <?php foreach(\Yii::$app->params['languages'] as $key => $language):?>
                    <?php if (\Yii::$app->language == $key) continue;?>
                    <li>
                        <a href="?<?= http_build_query(array_merge($_GET, ['l' => $key]))?>">
                            <img src="<?= $language['image']?>" alt="" /> <?= $language['name']?>
                        </a>
                    </li>
                    <?php endforeach;?>
                </ul>
            </div>
        </div>
        <div class="r-header">
            <div class="search">
                <div id="search" class="sb-search">
                    <form action="/search">
                        <input class="sb-search-input" type="text" value="" name="request" placeholder="<?= \Yii::t('app', 'Search')?>" />
                        <input class="sb-search-submit" type="submit" value="">
                        <span class="sb-icon-search" onclick="if($('.sb-search-input').val() != '') {$(this).closest('form').submit();} console.log($('.sb-search-input').val())"></span>
                    </form>
                </div>
            </div>
			<?php if (\Yii::$app->user->isGuest): ?>
            <ul class="sign">
                <li><a href="#sign-in" class="inline"><?= \Yii::t('app', 'sign in')?></a></li>
                <li><a href="#sign-up" class="inline"><?= \Yii::t('app', 'sign up')?></a></li>
            </ul>
			<?php else:?>
            <div class="user-es">
                <a href="#"><?= $this->params['user']->username?></a>
                <ul>
                    <li><a href="<?= Url::to('/profile/image')?>"><?= \Yii::t('app', 'images')?></a></li>
                    <li><a href="<?= Url::to('/profile/album')?>"><?= \Yii::t('app', 'albums')?></a></li>
                    <li><a href="<?= Url::to('/user/') . $this->params['user']->username?>"><?= \Yii::t('app', 'gallery profile')?></a></li>
                    <li><a href="<?= Url::to('/favorites')?>"><?= \Yii::t('app', 'favorites')?></a></li>
                    <li class="last">
						<a href="<?= Url::to('/profile/settings')?>"><?= \Yii::t('app', 'settings')?></a>
						<a href="<?= Url::toRoute('logout')?>"><?= \Yii::t('app', 'logout')?></a>
					</li>
                </ul>
            </div>
			<?php endif;?>
        </div>
    </div>
    <script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-79565783-1', 'auto');
  ga('send', 'pageview');

</script>
</header><!--.header-->
