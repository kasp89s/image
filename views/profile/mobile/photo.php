<?php
    use yii\helpers\Html;
?>
<?= $this->render('//mobile/block/header'); ?>
<div class="middle">
    <div class="container">
        <div class="c-content">
            <div class="galery-main my-photos">
                <?php if (!empty($startImages)): ?>
                    <?php foreach ($startImages as $key => $image): ?>
                        <div class="figure">
                            <div class="c-figure">
                                <a href="/profile/image-handler/<?= $image->id ?>">
                                    <img src="<?= $image->thumb160 ?>" alt="" />
                                </a>
                                <div class="over">
                                    <p><?= $image->title ?></p>
                                    <div>
                                        <span class="view"><?= $image->views?></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?= $this->render('//mobile/block/main_left'); ?>