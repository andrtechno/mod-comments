<?php

use yii\widgets\ActiveForm;
use panix\engine\Html;


$form = ActiveForm::begin([
    'id' => 'comment-reply-form',
    'action' => '/comments/reply/' . $reply->id,
    'enableAjaxValidation' => true,
]);
?>


<?php if (Yii::$app->user->isGuest) { ?>
    <div class="row">
        <div class="col-sm-6">
            <?= $form->field($model, 'user_name') ?>
        </div>
        <div class="col-sm-6">
            <?= $form->field($model, 'user_email') ?>
        </div>
    </div>
<?php } ?>
<?= $form->field($model, 'text')->textarea()->label(false); ?>

<div class="text-right" style="margin-top:10px;">
    <?= Html::submitButton(Yii::t('app', 'SEND'), ['class' => 'btn btn-success']) ?>
    <?php
    echo Html::a(Yii::t('app', 'Ответить'), 'javascript:void(0)', ['class' => 'btn btn-success']);
    ?>
</div>

<?php ActiveForm::end(); ?>
