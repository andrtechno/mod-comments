
<div id="comment_<?= $data->id ?>" name="comment_<?= $data->id ?>" class="comment col-xs-18">
    <div class="comment-content">
        <div class="comment-header">
            <b><?= Html::encode($data->user_name); ?></b> <span class="date"><?= CMS::date($data->date_create); ?></span>
        </div>
        <div id="comment_text_<?= $data->id; ?>" class="comment-text"><p><?= nl2br(CMS::bb_decode(Html::text($data->text))); ?></p></div>
    </div>
</div>






