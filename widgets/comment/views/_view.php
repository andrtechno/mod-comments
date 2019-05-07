<?php

use panix\engine\Html;
use panix\engine\CMS;

?>


<div class="row" id="comment-<?= $model->id ?>">
    <div class="col-sm-2 text-center">

        <img class="img-fluid rounded-circle comment-user-photo mb-3" src="https://ssl.gstatic.com/accounts/ui/avatar_2x.png">

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
                        $stime = strtotime($model->created_at) + Yii::$app->settings->get('comments', 'control_timeout');
                        $userId = Yii::$app->user->id;
                        if ($userId == $model->user_id) {
                            echo Html::a(Html::icon('edit'), 'javascript:void(0)', [
                                "onClick" => "$('#comment_" . $model->id . "').comment('update',{time:" . $stime . ", pk:" . $model->id . "}); return false;",
                                'class' => 'btn btn-primary btn-sm',
                                'title' => Yii::t('app', 'UPDATE')
                            ]);
                        }
                        ?>
                        <?php
                        $userId = Yii::$app->user->id;
                        if ($userId == $model->user_id) {
                            echo Html::a(Html::icon('delete'), ['/comments/default/delete','id'=>$model->id], [
                                'class' => 'btn btn-danger btn-sm comment-delete',
                                'data-pjax'=>'0',
                                'title' => Yii::t('app', 'DELETE')
                            ]);
                        }
                        ?>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <p id="comment_text_<?= $model->id; ?>"><?= nl2br(Html::text($model->text)); ?></p>
            </div>
        </div>
    </div>
</div>





