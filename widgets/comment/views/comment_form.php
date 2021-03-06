<?php

use yii\widgets\ActiveForm;
use panix\engine\Html;

/**
 * @var /yii/db/Model $object_id
 */

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
        <?= Html::activeHiddenInput($comment, 'handler_class', ['value' => $handler_class]); ?>

        <?php if (Yii::$app->user->isGuest) { ?>
            <div class="row">
                <div class="col-sm-6">
                    <?= $form->field($comment, 'user_name') ?>
                </div>
                <div class="col-sm-6">
                    <?= $form->field($comment, 'user_email') ?>
                </div>
            </div>
        <?php } ?>
        <?= $form->field($comment, 'text')->textarea() ?>
        <div class="form-group text-center">
            <?= Html::submitButton(Yii::t('app/default', 'SEND1'), ['class' => 'btn btn-success']) ?>
        </div>

        <?php ActiveForm::end(); ?>
    </div>
    <?php
    $this->registerJs("
    $(document).ready(function () {
        var xhr;
        $('#comment-create-form').on('beforeSubmit', function (e) {
            var form = $(this);
            var formData = form.serialize();
            
            if (xhr !== undefined)
                xhr.abort();
            
            xhr = $.ajax({
                url: form.attr('action'),
                type: form.attr('method'),
                data: formData,
                dataType: 'json',
                success: function (data) {

                    
                if (data.success) {
                    common.notify(data.message,'success');
                    $('#comments-text').val('');
                    $.pjax.reload('#pjax-grid-comments', {timeout : false});
                }else{


                        $.each(data.errors, function(i,error){
                            common.notify(error,'error');
                        });

                        common.notify(data.message,'error');

                }
                    

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
            'model' => $model,
        ]);
        ?>
    </div>
</div>