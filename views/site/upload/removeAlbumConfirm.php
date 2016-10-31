<div class="middle">
    <div class="container clearfix">
        <div class="column-left">
            <div class="block-main">
                <div class="delete-photo">
                    <h1><?= \Yii::t('app', 'Your album has been deleted')?>.</h1>
                    <p class="txt-center">
                        <a href="/" class="btn "><?= \Yii::t('app', 'Go back')?></a>
                    </p>
                </div>
            </div>
        </div>
        <? if (\Yii::$app->user->isGuest):?>
            <div class="right-column">
                <div class="block-main">
                    <header><?= \Yii::t('app', 'Create MegaSite Account')?></header>
                    <div class="c-block-main">
                        <p><?= \Yii::t('app', 'Register for an account and start having control over the images you upload')?>.</p>
                        <a class="inline" href="#sign-up"><?= \Yii::t('app', 'Register for an account')?></a><p></p>
                    </div>
                </div>
            </div>
        <? endif;?>
    </div>
</div>
