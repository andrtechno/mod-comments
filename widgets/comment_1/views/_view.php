<div id="commend_<?= $data->id ?>" name="commend_<?= $data->id ?>" class="comment">
    <div class="comment-author">
        <img src="<?= Html::encode($data->user->avatarPath); ?>">
    </div>
    <div class="comment-content">
        <div class="comment-header">
            <b><?= Html::encode($data->user_name); ?></b>, сказал: <?= Html::link('#' . $data->id, Yii::app()->request->getUrl() . '#comment_' . $data->id) ?>
            <div class="comment-date"><?= CMS::date($data->date_create); ?></div>
            <?php if ($data->controlTimeout()) { ?>
           
                    <div class="btn-group" id="comment-panel<?= $data->id ?>">
                    <?= $data->editLink ?>
                    <?= $data->deleteLink ?>

                </div>
            <?php } ?>
        </div>

        <div id="comment_<?= $data->id; ?>"><?= nl2br(CMS::bb_decode(Html::text($data->text))); ?></div>
    </div>


</div>






