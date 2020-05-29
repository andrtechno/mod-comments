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
    <?= Html::submitButton(Yii::t('app/default', 'SEND'), ['class' => 'btn btn-success']) ?>

</div>

<?php ActiveForm::end(); ?>


<?php
$this->registerJs("
    $(document).ready(function () {
        var xhr;
        $('#comment-reply-form').on('beforeSubmit', function (e) {
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
