<?php

use yii\widgets\ActiveForm;
use panix\engine\CMS;

//$currentUrl

?>

<?php


$form = ActiveForm::begin([
    'id' => 'comment-create-form',
    'action' => CMS::currentUrl(),
    'options' => [
        'class' => 'form-horizontal',
        'name' => 'comment-create-form'
    ],
]);
?>


<?= $form->field($model, 'text')->textarea()->label(false); ?>
    <div class="form-group text-center">
        <?= \panix\engine\Html::submitButton(Yii::t('app/default', 'UPDATE'), ['class' => 'btn btn-primary']) ?>
    </div>

<?php ActiveForm::end(); ?>