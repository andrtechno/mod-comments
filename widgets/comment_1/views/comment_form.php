
<script>
    var comment = {
        foodTime:<?= Yii::app()->settings->get('comments', 'flood_time') ?>,
        foodAlert:true
    };
</script>

<div class="help-block"><?= Yii::t('default', 'FORM_TEXT') ?></div>
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
    'htmlOptions' => array('name' => 'comment-create-form', 'class' => 'form-horizontal')
        ));

if ($comment->hasErrors())
    Yii::app()->tpl->alert('danger', Html::errorSummary($comment));

if (Yii::app()->user->hasFlash('success')) {
    Yii::app()->tpl->alert('success', Yii::app()->user->getFlash('success'));
}
?>
<?php //echo Html::hiddenField('object_id', $object_id); ?>
<?php //echo Html::hiddenField('owner_title', $owner_title); ?>
<?php //echo Html::hiddenField('model', $model); ?>
<?= $form->hiddenField($comment,'object_id', array('value'=>$object_id)); ?>
<?= $form->hiddenField($comment,'owner_title', array('value'=>$owner_title)); ?>
<?= $form->hiddenField($comment,'model', array('value'=>$model)); ?>
    <div class="alert" id="comment-alert" style="display: none;"></div>
<?php if(Yii::app()->user->isGuest){ ?>
<div class="form-group">
    <div class="col-sm-4">
        <?= $form->label($comment, 'user_name', array('class' => 'control-label')); ?>
    </div>
    <div class="col-sm-8">
        <?= $form->textField($comment, 'user_name', array('class' => 'form-control', 'placeholder' => $comment->getAttributeLabel('user_name'))); ?>
        <?= $form->error($comment, 'user_name'); ?>
    </div>
</div>
<?php }else{ ?>
<div class="form-group">
    <div class="col-sm-4">
        <?= $form->label($comment, 'user_name', array('class' => 'control-label')); ?>
    </div>
    <div class="col-sm-8">
        <?= Yii::app()->user->username; ?>
        <?php echo $form->hiddenField($comment,'user_name', array('value'=>Yii::app()->user->username)); ?>
    </div>
    </div>
<?php } ?>
<div class="form-group">
    <div class="col-sm-4">
        <?= $form->label($comment, 'text', array('class' => 'control-label')); ?>
    </div>
    <div class="col-sm-8">
        <?= $form->textArea($comment, 'text', array('class' => 'form-control', 'rows' => '5', 'placeholder' => $comment->getAttributeLabel('text'))); ?>
        <?= $form->error($comment, 'text'); ?>
    </div>
</div>



<div class="text-center">
    <?php
                echo Html::ajaxSubmitButton(Yii::t('default', 'SEND'), array('/comments/create'), array( //$currentUrl
                'type' => 'POST',
                'data' => 'js:$("#comment-create-form").serialize()',
                'dataType'=>'json',
                'success' => 'js:function(data) {
                    if(data.success){
                    
                        var ft = ' . time() . '+comment.foodTime;

                        $.session.set("caf",ft);
                        //$.fn.yiiListView.update("comment-list");
                       // $.jGrowl(data.message);
                        $("#comment-alert").removeClass("alert-danger").text(data.message).addClass("alert-success").show();
                    }else{
$("#comment-alert").removeClass("alert-success").text(data.message).addClass("alert-danger").show();
                    }
                     
                    }',
                'error' => 'js:function(jqXHR, textStatus, errorThrown ){
                    console.log(jqXHR);
                    
                }'
                    ), array('class' => 'btn btn-success'));
    
    ?>
</div>
<?php $this->endWidget(); ?>