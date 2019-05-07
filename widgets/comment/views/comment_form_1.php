
<?php
$form = $this->beginWidget('CActiveForm', array(
    'id' => 'comment-create-form',
    'action' => array('/comments/create'), //$currentUrl
    'enableClientValidation' => true,
    'enableAjaxValidation' => true, // Включаем аякс отправку
    'clientOptions' => array(
        'validateOnSubmit' => true,
        'validateOnChange' => true,
                            'errorCssClass' => 'has-error',
                    'successCssClass' => 'has-success',
    ),
    'htmlOptions' => array('class' => 'form', 'name' => 'comment-create-form')
        ));
?>




<script>
    var comment = {
        foodTime:<?= Yii::app()->settings->get('comments', 'flood_time') ?>,
        foodAlert:true
    };
</script>

<?php echo Html::hiddenField('object_id', $object_id); ?>
<?php echo Html::hiddenField('owner_title', $owner_title); ?>
<?php echo Html::hiddenField('handlerClass', $model); ?>


<div class="row">

        <div class="input-group">
            <span class="input-group-addon"><?php echo Html::image(Yii::app()->user->avatarPath); ?></span>
            <?php echo $form->textArea($comment, 'text',array('rows'=>4,'class'=>'form-control')); ?>
        </div>
    <div class="alert" id="comment-alert" style="display: none;"></div>
        <div class="text-right" style="margin-top:15px;">
                        <?php
            //echo Html::submitButton(Yii::t('default', 'SEND'), array('class' => 'btn btn-success', 'onclick' => 'fn.comment.add("#comment-create-form");'));
            echo Html::ajaxSubmitButton(Yii::t('default', 'SEND'), array('/comments/create'), array( //$currentUrl
                'type' => 'post',
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

</div>
<?php $this->endWidget(); ?>
