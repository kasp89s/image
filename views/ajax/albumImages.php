<?php if (!empty($images)):?>
    <?php foreach ($images as $image):?>
        <figure data-id="<?= $image->id?>" data-href="<?= $image->original?>" data-url="http://qruto.com/i/<?= $image->url?>">
            <a href="#poop-image-title" class="inline">
                <img src="<?= $image->thumb90?>" alt="<?= $image->title?>" />
            </a>
            <i class="icons"></i>
        </figure>
    <?php endforeach;?>
<?php endif;?>
