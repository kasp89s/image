<?php
use yii\helpers\Html;
use yii\helpers\Url;

$currentPage = 1;
$lastPage = ceil($pages->totalCount / Yii::$app->params['imagePerPageMain']);
?>
<script type="text/javascript">
var enable_scroll_loading = true;
</script>
<?= Html::input('hidden', 'currentPage', $currentPage, ['id' => 'currentPage'])?>
<?= Html::input('hidden', 'lastPage', $lastPage, ['id' => 'lastPage'])?>
<div class="middle">
<div class="container">
<div class="nav-main">
    <p><?= \Yii::t('app', 'Images')?>:</p>
    <div class="link-menu">
        <?php if ($sort == 'view') :?>
            <a href="javascript:void(0)"><?= \Yii::t('app', 'Most popular')?></a>,
        <?php endif;?>
        <?php if ($sort == 'date') :?>
            <a href="javascript:void(0)"><?= \Yii::t('app', 'Newest first')?></a>,
        <?php endif;?>
        <?php if ($sort == 'rand') :?>
            <a href="javascript:void(0)"><?= \Yii::t('app', 'Random mode')?></a>,
        <?php endif;?>
        <ul>
            <?php if ($sort != 'view') :?>
                <li><a href="?<?= http_build_query(array_merge($_GET, ['sort' => 'view']))?>"><?= \Yii::t('app', 'Most popular')?></a></li>
            <?php endif;?>
            <?php if ($sort != 'date') :?>
                <li><a href="?<?= http_build_query(array_merge($_GET, ['sort' => 'date']))?>"><?= \Yii::t('app', 'Newest first')?></a></li>
            <?php endif;?>
            <?php if ($sort != 'rand') :?>
                <li><a href="?<?= http_build_query(array_merge($_GET, ['sort' => 'rand']))?>"><?= \Yii::t('app', 'Random mode')?></a></li>
            <?php endif;?>
        </ul>
    </div>
    <p><?= \Yii::t('app', 'Category')?>:</p>
    <div class="link-menu">
        <a href="javascript:void(0)"><?= \Yii::t('app', $topic)?></a>
        <ul>
            <li><a href="?sort_topic=all"><?= \Yii::t('app', 'All images')?></a></li>
            <?php foreach (\Yii::$app->params['topicValues'] as $key => $value):?>
                <li><a href="?<?= http_build_query(array_merge($_GET, ['sort_topic' => $key]))?>"><?= \Yii::t('app', $value['title'])?></a></li>
            <?php endforeach;?>
        </ul>
    </div>
</div>
<div class="c-content">
<div class="galery-main">
<!-- <div class="figure es-vote active"> -->
    <input type="hidden" id="sort_topic" value="<?= $sort_topic?>" />
    <input type="hidden" id="sort" value="<?= $sort?>" />
<?php if (!empty($posts)):?>
<?php foreach ($posts as $post):?>
<?php
if (empty($post->images[0])) {var_dump($post); exit;}
$image = isset($post->images[0]->album->cover) ? $post->images[0]->album->cover : $post->images[0]->thumb160;
        ?>
<div class="figure
<?= (isset($_SESSION['likePost'][$post->id]) && $_SESSION['likePost'][$post->id] == 'inc') ? 'es-vote active' : ''?>
<?= (isset($_SESSION['likePost'][$post->id]) && $_SESSION['likePost'][$post->id] == 'dec') ? 'no-vote active' : ''?>
">
    <div class="c-figure">
        <a href="<?= Url::to('/' . $post->url)?>">
            <img src="<?= $image?>" alt="" />
        </a>
        <div class="over" data-count="<?= $post->likeCount?>">
            <a href="javascript:void(0)"
               class="link-top <?= (isset($_SESSION['likePost'][$post->id]) && $_SESSION['likePost'][$post->id] == 'inc') ? 'active' : ''?>"
               onclick="like('<?= $post->id?>', 'inc', this)"></a>
            <a href="javascript:void(0)"
               class="link-bot <?= (isset($_SESSION['likePost'][$post->id]) && $_SESSION['likePost'][$post->id] == 'dec') ? 'active' : ''?>"
               onclick="like('<?= $post->id?>', 'dec', this)"></a>
            <span class="view"><?= $post->likeCount?></span>
        </div>
    </div>
    <div class="toltip">
        <div class="txt-toltip">
            <p><?= $post->title?></p>
        </div>
        <p class="txt-right"><?= $post->viewCount?> <?= \Yii::t('app', 'views')?></p>
    </div>
</div>
<? endforeach;?>
<? endif;?>
</div>
<p class="txt-center"><i class="ajax-loader" style="display: none;"></i></p>
</div>
</div>
</div>
