<?php

use yii\helpers\Html;
use panix\engine\bootstrap\ActiveForm;

/**
 * @var \panix\mod\comments\models\Comments $model
 */

?>

<?php
$form = ActiveForm::begin();
?>

    <div class="card">
        <div class="card-header">
            <h5><?= Html::encode($this->context->pageName) ?></h5>
        </div>
        <div class="card-body">

            <?php
            echo $form->field($model, 'switch')->dropDownList($model::getStatuses());

            ?>
            <?php
            echo $form->field($model, 'text')->widget(\panix\ext\tinymce\TinyMce::class, [
                'options' => ['rows' => 6],
            ]);

            ?>
        </div>
        <div class="card-footer text-center">
            <?= $model->submitButton(); ?>
        </div>
    </div>
<?php ActiveForm::end(); ?>