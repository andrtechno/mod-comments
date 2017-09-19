<?php

use yii\widgets\ActiveForm;
use panix\engine\Html;
?>


<?php
echo $this->render('comment_list', [
    'dataProvider' => $dataProvider,
]);
?>

<script>
    var comment = {
        foodTime:<?= Yii::$app->settings->get('comments', 'flood_time') ?>,
        foodAlert: true
    };
</script>
<?= Yii::t('comments/default', 'FORM_TEXT') ?>







<?php
$form = ActiveForm::begin([
            'id' => 'comment-create-form',
            'action' => ['/comments/add'],
            'options' => [
                'class' => 'form-horizontal',
                'name' => 'comment-create-form'
            ],
        ]);
?>

<?= $form->field($comment, 'object_id')->hiddenInput(['value' => $object_id])->label(false); ?>
<?= $form->field($comment, 'owner_title')->hiddenInput(['value' => $owner_title])->label(false); ?>
<?= $form->field($comment, 'model')->hiddenInput(['value' => $model])->label(false); ?>
<?php if (Yii::$app->user->isGuest) { ?>
    <?= $form->field($comment, 'user_name') ?>
    <?= $form->field($comment, 'user_email') ?>
<?php } ?>
<?= $form->field($comment, 'text')->textarea() ?>
<?= Html::submitButton(Yii::t('app', 'SAVE'), ['class' => 'btn btn-success']) ?>


<?php ActiveForm::end(); ?>


<script>
    $('#comment-create-form').on('beforeSubmit', function (e) {
        var form = $(this);
        var formData = form.serialize();
        $.ajax({
            url: form.attr("action"),
            type: form.attr("method"),
            data: formData,
            dataType: 'json',
            success: function (data) {
                alert('Test');
            },
            error: function () {
                alert("Something went wrong");
            }
        });
    }).on('submit', function (e) {
        e.preventDefault();
    });
</script>