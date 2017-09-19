

        <h3 class="title-review-comments"><?= $dataProvider->totalCount?> comments</h3>
        <?php
        echo \yii\widgets\ListView::widget([
                'id' => 'comment-list',
            'dataProvider' => $dataProvider,
            'itemView' => '_view',
            'layout' => '{summary}{items}{pager}',
            'emptyText' => 'Empty',
            'options' => ['class' => 'row list-view'],
            'itemOptions' => ['class' => 'item'],
            'pager' => [
                'class' => \kop\y2sp\ScrollPager::className(),
                'triggerTemplate' => '<div class="ias-trigger" style="text-align: center; cursor: pointer;"><a href="javascript:void(0)">{text}</a></div>'
            ],
            'emptyTextOptions' => ['class' => 'alert alert-info']
        ]);
        ?>
<?php
/*$this->widget('ListView', array(
    'dataProvider' => $dataProvider,
    'id' => 'comment-list',
    'emptyText' => Yii::t('CommentsModule.default', 'NO_COMMENTS'),
    'itemView' => (Yii::app()->controller instanceof AdminController) ? '_view_backend' : '_view',
    'itemsCssClass' => 'items',
    'pager' => array(
        'header' => '',
        'nextPageLabel' => false,
        'prevPageLabel' => false,
        'firstPageLabel' => false,
        'lastPageLabel' => false,
    )
));
*/
?>
