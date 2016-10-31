<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;
?>
<div class="container">
    <div class="row">
        <div class="col-md-4 col-md-offset-4">
            <div class="login-panel panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Вход</h3>
                </div>
                <div class="panel-body">
                    <?php $form = ActiveForm::begin(); ?>
                    <fieldset>
                        <?= $form->field($model, 'username') ?>

                        <?= $form->field($model, 'password')->input('password') ?>

                        <div class="checkbox">
                            <label>
                                <?= Html::activeCheckbox($model, 'rememberMe')?>
                            </label>
                        </div>
                        <!-- Change this to a button or input when using this as a form -->
                        <?= Html::submitButton('Войти', ['class' => 'btn btn-lg btn-success btn-block']) ?>
                    </fieldset>
                    <?php ActiveForm::end(); ?>
                </div>
            </div>
        </div>
    </div>
</div>
