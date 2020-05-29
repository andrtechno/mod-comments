<?php

use panix\engine\widgets\Pjax;
use yii\widgets\ListView;

Pjax::begin([
    'id' => 'pjax-comments',
    'dataProvider' => $dataProvider,
]);
?>
<div class="heading-gradient">
    <h3>
        <?= Yii::t('app/default', 'REVIEWS', ['n' => $model->commentsCount]) ?>
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


