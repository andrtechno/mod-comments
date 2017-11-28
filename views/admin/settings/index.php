<?php

use panix\engine\Html;
use panix\engine\bootstrap\ActiveForm;
?>
<?php
$form = ActiveForm::begin([
            //  'id' => 'form',

            'options' => ['class' => 'form-horizontal'],
        ]);
?>
<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title"><?= $this->context->pageName ?></h3>
    </div>
    <div class="panel-body">

        <?= $form->field($model, 'pagenum'); ?>

        <?= $form->field($model, 'allow_add'); ?>
        <?= $form->field($model, 'allow_view'); ?>
        <?= $form->field($model, 'flood_time'); ?>
        <?= $form->field($model, 'control_timeout'); ?>




        <?php echo $form->field($model, 'create_btn_action')->dropDownList($model::getButtonIconSizeList(),[])  ?>
    </div>
    <div class="panel-footer text-center">
        <?= Html::submitButton(Yii::t('app', 'SAVE'), ['class' => 'btn btn-success']) ?>
    </div>
</div>
<?php ActiveForm::end(); ?>