<?php

use panix\engine\widgets\Pjax;
use yii\widgets\ListView;

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
<div class="heading-gradient">
    <h3>
        <?= Yii::t('app', 'REVIEWS', ['n' => $dataProvider->totalCount]) ?>
    </h3>
</div>
<?php
echo ListView::widget([
    'id' => 'comment-list',
    'dataProvider' => $dataProvider,
    'itemView' => '_view',
    'layout' => '{items}{pager}',
    'emptyText' => Yii::t('comments/default', 'NO_COMMENTS'),
    'options' => ['class' => 'list-view'],
    'itemOptions' => ['class' => 'comment-item'],
    'emptyTextOptions' => ['class' => 'alert alert-info'],
    'pager' => [
        'class' => \panix\engine\widgets\LinkPager::class
    ]
]);
Pjax::end();
?>

