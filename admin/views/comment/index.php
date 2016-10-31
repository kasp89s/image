<?php
use yii\helpers;
use yii\helpers\Html;
use yii\widgets\LinkPager;
?>
<div id="page-wrapper">
    <div class="row">
        <!-- /.col-lg-12 -->
            <div class="col-lg-12">
                <h3 class="page-header">Коментарии</h3>
            </div>
            <div class="col-lg-9">
                <? if (!empty($comments)):?>
                    <table class="table table-striped" id="dataTables-example">
                        <thead>
                        <tr>
                            <th>Текст</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        <? foreach ($comments as $item):?>
                            <tr>
                                <td><?= $item->message?></td>
                                <td>
                                    <a href="<?= helpers\Url::toRoute(['remove-comment', 'id' => $item->id]);?>" class="fa fa-times"></a>
                                </td>
                            </tr>
                        <? endforeach;?>
                        </tbody>
                    </table>
                <? endif;?>
                <?php

                echo LinkPager::widget([
                        'pagination' => $commentsPages,
                    ]);
                ?>
            </div>
        <div class="col-lg-12">
                <h3 class="page-header">Ответы</h3>
            </div>
            <div class="col-lg-9">
                <? if (!empty($replies)):?>
                    <table class="table table-striped" id="dataTables-example">
                        <thead>
                        <tr>
                            <th>Текст</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        <? foreach ($replies as $item):?>
                            <tr>
                                <td><?= $item->message?></td>
                                <td>
                                    <a href="<?= helpers\Url::toRoute(['remove-reply', 'id' => $item->id]);?>" class="fa fa-times"></a>
                                </td>
                            </tr>
                        <? endforeach;?>
                        </tbody>
                    </table>
                <? endif;?>
                <?php

                echo LinkPager::widget([
                        'pagination' => $repliesPages,
                    ]);
                ?>
            </div>
    </div>
    <!-- /.row -->
</div>
