<?php

use panix\engine\widgets\Pjax;
?>
<?php
Pjax::begin([
    'id' => 'pjax-comments',
    'timeout' => 50000,
    'enablePushState' => false,
    'clientOptions' => [

        'timeout' => 50000,
    ]
]);
?>

<h3 class="title-review-comments"><?= $dataProvider->totalCount ?> comments</h3>

<?php
echo \yii\widgets\ListView::widget([
    'id' => 'comment-list',
    'dataProvider' => $dataProvider,
    'itemView' => '_view',
    'layout' => '{items}{pager}',
    'emptyText' => Yii::t('comments/default', 'NO_COMMENTS'),
    'options' => ['class' => 'row list-view'],
    'itemOptions' => ['class' => 'item'],
    'emptyTextOptions' => ['class' => 'alert alert-info']
]);
Pjax::end();
?>

