<?php

use panix\engine\Html;
use panix\engine\CMS;

?>


<div class="row" id="comment-<?= $model->id ?>" name="comment-<?= $model->id ?>">
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
                        <span class="badge"><?= CMS::date(date('Y-m-d H:i:s', $model->created_at)); ?></span>

                            <a href="#button" class="btn btn-sm btn-warning"><i class="icon-edit"></i></a>
                            <a href="#button" class="btn btn-sm btn-danger"><i class="icon-delete"></i></a>

                    </div>
                </div>
            </div>
            <div class="card-body">
                <p id="comment_text_<?= $model->id; ?>"><?= nl2br(Html::text($model->text)); ?></p>
            </div>
        </div>
    </div>
</div>





