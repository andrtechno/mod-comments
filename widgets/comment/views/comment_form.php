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
<div class="review-form">
    <div class="form-container">
        <h3 class="text-center"><?= Yii::t('comments/default', 'FORM_TEXT') ?></h3>
        <br/><br/>

        <div class="alert" id="comment-alert" style="display: none;"></div>
        <?php
        // if ($comment->hasErrors())
        //Yii::$app->tpl->alert('danger', Html::errorSummary($comment));
        // if (Yii::$app->user->hasFlash('success')) {
        ?>
        <?php
        // Yii::$app->tpl->alert('success', Yii::$app->user->getFlash('success'));
        //  }
        ?>


        <?php
        $form = ActiveForm::begin([
                    'id' => 'comment-create-form',
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





        <?php
        /* echo Html::ajaxSubmitButton(Yii::t('default', 'Оставить отзыв'), array('/comments/create'), array(//$currentUrl
          'type' => 'POST',
          'data' => 'js:$("#comment-create-form").serialize()',
          'dataType' => 'json',
          'success' => 'js:function(data) {
          if(data.success){

          var ft = ' . CMS::time() . '+comment.foodTime;

          $.session.set("caf",ft);
          if(data.grid_update){
          $.fn.yiiListView.update("comment-list");
          }
          // $.jGrowl(data.message);
          $("#comment-alert").removeClass("alert-danger").text(data.message).addClass("alert-success").show().delay(3000).fadeOut(2500);
          $("#comment-create-form").remove();
          }else{
          $("#comment-alert").removeClass("alert-success").text(data.message).addClass("alert-danger").show()


          }

          }',
          'error' => 'js:function(jqXHR, textStatus, errorThrown ){
          console.log(jqXHR);

          }'
          ), array('class' => 'btn btn-primary btn-upper'))* */
        ?>

        <?php ActiveForm::end(); ?>


        <script>
            $('#comment-create-form').on('beforeSubmit', function (e) {
                var form = $(this);
                var formData = form.serialize();
                $.ajax({
                    url: form.attr("action"),
                    type: form.attr("method"),
                    data: formData,
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