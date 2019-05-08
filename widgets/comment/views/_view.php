<?php

use panix\engine\Html;
use panix\engine\CMS;

?>


<div class="row" id="comment-<?= $model->id ?>">
    <div class="col-sm-2 text-center">

        <img class="img-fluid rounded-circle comment-user-photo mb-3"
             src="https://ssl.gstatic.com/accounts/ui/avatar_2x.png">

    </div>

    <div class="col-sm-10">
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col-sm-4">
                        <div class="comment-user-name"><?= Html::encode($model->user_name); ?></div>
                    </div>
                    <div class="col-sm-8 text-right">
                        <span class="badge badge-light"><?= CMS::date($model->created_at); ?></span>

                        <?php
                        //if ($model->hasAccessControl()) {
                            echo Html::a(Html::icon('edit'), ['/comments/default/update', 'id' => $model->id], [
                                'class' => 'btn btn-sm btn-primary comment-update',
                                'data-id'=>$model->id,
                                'title' => Yii::t('app', 'UPDATE')
                            ]);
                        //}
                        ?>
                        <?php
                        if ($model->hasAccessControl()) {
                            echo Html::a(Html::icon('delete'), ['/comments/default/delete', 'id' => $model->id], [
                                'class' => 'btn btn-sm btn-danger comment-delete',
                                'data-pjax' => '0',
                                'data-id'=>$model->id,
                                'title' => Yii::t('app', 'DELETE')
                            ]);
                        }
                        ?>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div id="comment_text_<?= $model->id; ?>"><?= nl2br(Html::text($model->text)); ?></div>
            </div>
        </div>
    </div>
</div>





