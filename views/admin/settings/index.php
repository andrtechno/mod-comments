<?php

use panix\engine\Html;
use panix\engine\bootstrap\ActiveForm;
?>
<?php
$form = ActiveForm::begin([
            'options' => ['class' => 'form-horizontal'],
        ]);
?>
<div class="card">
    <div class="card-header">
        <h5><?= $this->context->pageName ?></h5>
    </div>
    <div class="card-body">

        <?= $form->field($model, 'pagenum'); ?>

        <?= $form->field($model, 'allow_add'); ?>
        <?= $form->field($model, 'allow_view'); ?>
        <?= $form->field($model, 'flood_time'); ?>
        <?= $form->field($model, 'control_timeout'); ?>


    </div>
    <div class="card-footer text-center">
        <?= Html::submitButton(Yii::t('app', 'SAVE'), ['class' => 'btn btn-success']) ?>
    </div>
</div>
<?php ActiveForm::end(); ?>