
<script>
    var comment = {
        foodTime:<?= Yii::app()->settings->get('comments', 'flood_time') ?>,
        foodAlert: true
    };
</script>

<div class="row">
    <div class="col-xs-12">
    <hr/>
    <h2><?= Yii::t('CommentsModule.default', 'FORM_TEXT') ?></h2>
    <br/><br/>

    <?php
   /* $form = $this->beginWidget('CActiveForm', array(
        'id' => 'comment-create-form2',
        'enableAjaxValidation' => false, // Disabled to prevent ajax calls for every field update
        'enableClientValidation' => true,
        'clientOptions' => array(
            'validateOnSubmit' => true,
            'validateOnChange' => true,
            'errorCssClass' => 'has-error',
            'successCssClass' => 'has-success',
        ),
        'htmlOptions' => array('name' => 'comment-create-form2', 'class' => 'form-horizontal text-left')
    ));*/

    
    echo Html::form('','POST');

  //  if ($comment->hasErrors())
   //     Yii::app()->tpl->alert('danger', Html::errorSummary($comment));

    if (Yii::app()->user->hasFlash('success')) {
        Yii::app()->tpl->alert('success', Yii::app()->user->getFlash('success'));
    }
    ?>

    <?= Html::activeHiddenField($comment, 'object_id', array('value' => $object_id)); ?>
    <?= Html::activeHiddenField($comment, 'owner_title', array('value' => $owner_title)); ?>
    <?= Html::activeHiddenField($comment, 'handlerClass', array('value' => $model)); ?>
    <?= Html::activeHiddenField($comment, 'user_name'); ?>
    <?= Html::activeHiddenField($comment, 'user_email'); ?>
    <?= Html::activeHiddenField($comment, 'switch', array('value' => 1)); ?>
       <div class="col-xs-12">
    <div class="alert" id="comment-alert" style="display: none;"></div>
   </div>
    <div class="form-group">
        <div class="col-xs-12">
            <?= Html::activeTextArea($comment, 'text', array('class' => 'form-control', 'rows' => '5', 'placeholder' => 'Ваш отзыв')); ?>
            <?= Html::error($comment, 'text'); ?>
        </div>
    </div>

    <div class=" form-group text-center">
        <?php
        echo Html::ajaxSubmitButton(Yii::t('default', 'Оставить отзыв'), array('/comments/create'), array(//$currentUrl
            'type' => 'POST',
            'data' => 'js:$("#ShopProduct").serialize()',
            'dataType' => 'json',
            'success' => 'js:function(data) {
                    if(data.success){
                    
                        var ft = ' . CMS::time() . '+comment.foodTime;

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
                ), array('class' => 'btn btn-danger'));
        ?>
    </div>
    <?php echo Html::endForm(); //$this->endWidget(); ?>
</div>
</div>