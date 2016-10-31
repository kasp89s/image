<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use yii\helpers;
?>
<div id="page-wrapper">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header"><?= $title?></h1>
        </div>
        <!-- /.col-lg-12 -->
        <div class="col-lg-6">
            <?php $form = ActiveForm::begin(); ?>

            <?= $form->field($model, 'type')->dropDownList([
					'main' => 'Главное меню',
					'left' => 'Левое меню',
					'test' => 'Тесты',
					'common' => 'Стандарт',
				]);?>
            <?= $form->field($model, 'alias') ?>

            <?= $form->field($model, 'title') ?>

            <?= $form->field($model, 'shortContent')->textArea(['rows' => '3']) ?>
			
            <?= $form->field($model, 'content')->textArea(['rows' => '5']) ?>

            <?= Html::submitButton('Cохранить', ['class' => 'btn btn-success']) ?>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
    <!-- /.row -->
</div>
<!-- /#page-wrapper -->
