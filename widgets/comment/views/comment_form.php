<?php

use yii\widgets\ActiveForm;
use panix\engine\Html;

?>


<script>
    var comment = {
        foodTime:<?= Yii::$app->settings->get('comments', 'flood_time') ?>,
        foodAlert: true
    };
</script>

<div class="text-center">
<h2 class="heading-gradient"><?= Yii::t('comments/default', 'FORM_TEXT'); ?></h2>
</div>
<div class="row">
    <div class="col-lg-6 offset-lg-3">
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
        <?= Html::activeHiddenInput($comment, 'object_id', ['value' => $object_id]); ?>
        <?= Html::activeHiddenInput($comment, 'owner_title', ['value' => $owner_title]); ?>
        <?= Html::activeHiddenInput($comment, 'handlerClass', ['value' => $model]); ?>

        <?php if (Yii::$app->user->isGuest) { ?>
            <?= $form->field($comment, 'user_name') ?>
            <?= $form->field($comment, 'user_email') ?>
        <?php } ?>
        <?= $form->field($comment, 'text')->textarea() ?>
        <div class="form-group text-center">
            <?= Html::submitButton(Yii::t('app', 'SEND'), ['class' => 'btn btn-success']) ?>
        </div>

        <?php ActiveForm::end(); ?>
    </div>
    <?php
    $this->registerJs("
    $(document).ready(function () {
        $('#comment-create-form').on('beforeSubmit', function (e) {
            var form = $(this);
            var formData = form.serialize();
            $.ajax({
                url: form.attr('action'),
                type: form.attr('method'),
                data: formData,
                dataType: 'json',
                success: function (data) {
                    var test = $.pjax.reload('#pjax-comments', {timeout : false});
                    common.notify(data.message,'success');
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    console.log(jqXHR);
                    console.log(textStatus);
                    console.log(errorThrown);
                }
            });
        }).on('submit', function (e) {
            e.preventDefault();
        });
    });
     ", yii\web\View::POS_END, 'comment-send');
    ?>
    <div class="col-lg-10 offset-lg-1">
        <?php
        echo $this->render('comment_list', [
            'dataProvider' => $dataProvider,
        ]);
        ?>
    </div>
</div>