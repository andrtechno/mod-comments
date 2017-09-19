<div id="comment_<?= $data->id ?>" name="comment_<?= $data->id ?>" class="row">
    <div class="col-md-2 col-sm-2">
        <?= Html::image($data->getAvatarUrl('70x70'), '', array('class' => 'img-rounded img-responsive')); ?>
    </div>
    <div class="col-md-10 col-sm-10 blog-comments outer-bottom-xs">
        <div class="blog-comments inner-bottom-xs">
            <h4><?= Html::encode($data->user_name); ?></h4>
            <span class="review-action pull-right">
                <?= CMS::date($data->date_create); ?> &sol;   
                <a href=""> Repost</a> &sol;
                <a href=""> Reply</a>
            </span>
            <p id="comment_text_<?= $data->id; ?>"><?= nl2br(CMS::bb_decode(Html::text($data->text))); ?></p>
        </div>
    </div>
</div>








