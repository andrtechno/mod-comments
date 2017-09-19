
<script>
    var comment = {
        foodTime:<?= Yii::app()->settings->get('comments', 'flood_time') ?>,
        foodAlert: true
    };
</script>
<div class="review-form">
    <div class="form-container">
        <h3 class="text-center"><?= Yii::t('CommentsModule.default', 'FORM_TEXT') ?></h3>
        <br/><br/>

        <div class="alert" id="comment-alert" style="display: none;"></div>
        <?php
        if ($comment->hasErrors())
            Yii::app()->tpl->alert('danger', Html::errorSummary($comment));

        if (Yii::app()->user->hasFlash('success')) {
            ?>
            <?php
            Yii::app()->tpl->alert('success', Yii::app()->user->getFlash('success'));
        }
        ?>

        <?php
        $form = $this->beginWidget('CActiveForm', array(
            'id' => 'comment-create-form',
            'enableAjaxValidation' => false, // Disabled to prevent ajax calls for every field update
            'enableClientValidation' => true,
            'clientOptions' => array(
                'validateOnSubmit' => true,
                'validateOnChange' => true,
                'errorCssClass' => 'has-error',
                'successCssClass' => 'has-success',
            ),
            'htmlOptions' => array('name' => 'comment-create-form', 'class' => '2form-horizontal 2form-vertical2 text-left')
        ));
        ?>
        <?= $form->hiddenField($comment, 'object_id', array('value' => $object_id)); ?>
        <?= $form->hiddenField($comment, 'owner_title', array('value' => $owner_title)); ?>
        <?= $form->hiddenField($comment, 'model', array('value' => $model)); ?>
        <div class="row">
            <?php if (Yii::app()->user->isGuest) { ?>
                <div class="col-sm-6">
                    <div class="form-group">
                        <?= $form->labelEx($comment, 'user_name'); ?>
                        <?= $form->textField($comment, 'user_name', array('class' => 'form-control txt', 'placeholder' => 'Ваше имя')); ?>
                        <?= $form->error($comment, 'user_name'); ?>
                    </div>
                    <div class="form-group">
                        <?= $form->labelEx($comment, 'user_email'); ?>
                        <?= $form->textField($comment, 'user_email', array('class' => 'form-control txt', 'placeholder' => 'E-mail')); ?>
                        <?= $form->error($comment, 'user_email'); ?>
                    </div>
                </div>
            <?php } ?>
            <div class="col-md-<?php if (Yii::app()->user->isGuest) { ?>6<?php } else { ?>12<?php } ?>">
                <div class="form-group">
                    <?= $form->labelEx($comment, 'text'); ?>
                    <?= $form->textArea($comment, 'text', array('class' => 'form-control txt txt-review', 'rows' => '4', 'placeholder' => 'Ваш отзыв')); ?>
                    <?= $form->error($comment, 'text'); ?>
                </div>
            </div>
        </div>

        <div class="action text-right">

            <?php
            echo Html::ajaxSubmitButton(Yii::t('default', 'Оставить отзыв'), array('/comments/create'), array(//$currentUrl
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
                    ), array('class' => 'btn btn-primary btn-upper'));
            ?>
        </div><!-- /.action -->

        <?php $this->endWidget(); ?>
    </div><!-- /.form-container -->
</div><!-- /.review-form -->
