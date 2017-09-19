
<script>
    var comment = {
        foodTime:<?= Yii::app()->settings->get('comments', 'flood_time') ?>,
        foodAlert: true
    };
</script>

<div class="text-center row">
    <div class="col-sm-10 col-sm-offset-4">
    <hr/>
    <h2><?= Yii::t('CommentsModule.default', 'FORM_TEXT') ?></h2>
    <br/><br/>
    <div class="alert" id="comment-alert" style="display: none;"></div>
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
        'htmlOptions' => array('name' => 'comment-create-form', 'class' => 'form-horizontal form-vertical2 text-left')
    ));

    if ($comment->hasErrors())
        Yii::app()->tpl->alert('danger', Html::errorSummary($comment));

    if (Yii::app()->user->hasFlash('success')) {?>
    <?php
        Yii::app()->tpl->alert('success', Yii::app()->user->getFlash('success'));
    }
    ?>

    <?= $form->hiddenField($comment, 'object_id', array('value' => $object_id)); ?>
    <?= $form->hiddenField($comment, 'owner_title', array('value' => $owner_title)); ?>
    <?= $form->hiddenField($comment, 'model', array('value' => $model)); ?>


    <div class="form-group">
        <div class="col-xs-18">
            <?= $form->textArea($comment, 'text', array('class' => 'form-control', 'rows' => '5', 'placeholder' => 'Ваш отзыв')); ?>
            <?= $form->error($comment, 'text'); ?>
        </div>
    </div>

        
        <div class="col-xs-18 col-sm-9">
            <div class="form-group">
                <?= $form->textField($comment, 'user_name', array('class' => 'form-control', 'placeholder' => 'Ваше имя')); ?>
                <?= $form->error($comment, 'user_name'); ?>
            </div>
        </div>
         
        <div class="col-xs-18 col-sm-9">
           <div class="form-group">
                <?= $form->textField($comment, 'user_email', array('class' => 'form-control', 'placeholder' => 'E-mail')); ?>
                <?= $form->error($comment, 'user_email'); ?>
            </div>
        </div>



    <div class=" form-group text-center">
        <?php
        echo Html::ajaxSubmitButton(Yii::t('default', 'Оставить отзыв'), array('/comments/create'), array(//$currentUrl
            'type' => 'POST',
            'data' => 'js:$("#comment-create-form").serialize()',
            'dataType' => 'json',
            'success' => 'js:function(data) {
                    if(data.success){
                    
                        var ft = ' . time() . '+comment.foodTime;

                        $.session.set("caf",ft);
                        //$.fn.yiiListView.update("comment-list");
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
                ), array('class' => 'btn btn-danger'));
        ?>
    </div>
    <?php $this->endWidget(); ?>
</div>
</div>