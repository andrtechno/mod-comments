<?php

use panix\engine\Html;
use panix\engine\CMS;
?>


<div class="row" id="comment_<?= $model->id ?>" name="comment_<?= $model->id ?>">
    <div class="col-sm-1">
        <div class="thumbnail">
            <img class="img-responsive user-photo" src="https://ssl.gstatic.com/accounts/ui/avatar_2x.png">
        </div>
    </div>

    <div class="col-sm-5">
        <div class="panel panel-default">
            <div class="panel-heading">
                <strong><?= Html::encode($model->user_name); ?></strong> <span class="text-muted"><?= CMS::date($model->date_create); ?></span>
            </div>
            <div class="panel-body">
                <p id="comment_text_<?= $model->id; ?>"><?= nl2br(Html::text($model->text)); ?></p>
            </div>
        </div>
    </div>
</div>





