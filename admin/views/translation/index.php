<?php
use yii\helpers;
use yii\helpers\Html;
?>
<div id="page-wrapper">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header"><?= $title?></h1>
        </div>
        <!-- /.col-lg-12 -->
        <?= Html::beginForm('', 'post'); ?>
            <div class="col-lg-12">
                <a href="<?= helpers\Url::toRoute(['create']);?>" class="btn btn-success">Найти новые переводы</a>
                <button type="submit" class="btn btn-success">Сохранить</button>
            </div>
            <div class="col-lg-9">
                <? if (!empty($translations)):?>
                    <table class="table table-striped" id="dataTables-example">
                        <thead>
                        <tr>
                            <th>Ключь</th>
                            <th>Значение</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        <? foreach ($translations as $key => $value):?>
                            <tr>
                                <td><?= $key?></td>
                                <td><input name="translations[<?= $key?>]" value="<?= $value?>"/></td>
                            </tr>
                        <? endforeach;?>
                        </tbody>
                    </table>
                <? endif;?>
            </div>
            <div class="col-lg-12">
                <button type="submit" class="btn btn-success">Сохранить</button>
            </div>
        <?= Html::endForm();?>
    </div>
    <!-- /.row -->
</div>
