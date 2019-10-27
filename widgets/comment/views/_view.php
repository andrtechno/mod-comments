<?php

use panix\engine\Html;
use panix\engine\CMS;
/** @var $model \panix\mod\comments\models\Comments */
?>


<div class="row" id="comment-<?= $model->id ?>">
    <div class="col-sm-3 col-md-2 text-center text-md-right">
        <img src="<?= $model->getAvatarUrl(); ?>" class="rounded-circle img-thumbnail comment-user-photo mb-3">
    </div>

    <div class="col-sm-9 col-md-10">
        <div class="card">
            <div class="card-header">
                <div class="">

                    <div class="float-left">
                        <div class="comment-user-name">
                            <?php
                            if ($model->user_id) {
                                echo Html::icon('check', ['class' => 'text-success']);
                            }
                            ?>
                            <strong><?= Html::encode($model->user_name); ?></strong>
                            <?php
                            if (!$model->user_id) {
                                echo Html::tag('span', '(гость)', ['class' => 'badge badge-light']);
                            }
                            ?>
                        </div>

                    </div>
                    <div class="float-right">
                        <span class="badge badge-light"><?= CMS::date($model->created_at); ?></span>

                        <?php
                        //if ($model->hasAccessControl()) {
                        echo Html::a(Html::icon('edit'), ['/comments/default/update', 'id' => $model->id], [
                            'class' => 'btn btn-sm btn-primary comment-update',
                            'data-id' => $model->id,
                            'title' => Yii::t('app', 'UPDATE')
                        ]);
                        //}
                        ?>
                        <?php
                        if ($model->hasAccessControl()) {
                            echo Html::a(Html::icon('delete'), ['/comments/default/delete', 'id' => $model->id], [
                                'class' => 'btn btn-sm btn-danger comment-delete',
                                'data-pjax' => '0',
                                'data-id' => $model->id,
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
            <div class="card-footer text-right">
                <?php
                echo \panix\engine\widgets\like\LikeWidget::widget([
                    'model' => $model
                ]);
                ?>
                <?= Html::a('Ответить', ['/comments/default/reply', 'id' => $model->id], [
                    'data-id' => $model->id,
                    'class' => 'btn btn-sm comment-reply btn-link'
                ]); ?>
            </div>
        </div>
        <div class="container-reply" id="container-reply-<?= $model->id ?>">
            <?php
            // print_r($model->query);
            $descendants = $model->children()->orderBy(['id' => SORT_DESC])->all();
            foreach ($descendants as $data) { ?>


                <?= $this->render('_view', ['model' => $data]); ?>


            <?php } ?>

        </div>
    </div>
</div>





