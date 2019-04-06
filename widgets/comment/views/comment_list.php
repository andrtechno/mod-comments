<?php

use panix\engine\widgets\Pjax;
?>
<?php
Pjax::begin([
    'id' => 'pjax-comments',
 /*   'timeout' => 50000,
    'enablePushState' => false,
    'clientOptions' => [

        'timeout' => 50000,
    ]*/
]);
?>

<h3 class="heading-gradient"><?= Yii::t('app', 'REVIEWS', ['n' => $dataProvider->totalCount]) ?></h3>

<?php
echo \yii\widgets\ListView::widget([
    'id' => 'comment-list',
    'dataProvider' => $dataProvider,
    'itemView' => '_view',
    'layout' => '{items}{pager}',
    'emptyText' => Yii::t('comments/default', 'NO_COMMENTS'),
    'options' => ['class' => 'list-view'],
    'itemOptions' => ['class' => 'item comment-item'],
    'emptyTextOptions' => ['class' => 'alert alert-info']
]);
Pjax::end();
?>

