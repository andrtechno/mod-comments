<?php

use yii\helpers\Html;
use panix\engine\grid\GridView;
use panix\engine\widgets\Pjax;

Pjax::begin([
    'id'=>  'pjax-'.strtolower(basename($dataProvider->query->modelClass)),
]);

//echo 'pjax-'.strtolower(basename($dataProvider->query->modelClass));
//echo Html::beginForm(['/admin/pages/default/test'],'post',['id'=>'test','name'=>'test']);
echo GridView::widget([
    'tableOptions' => ['class' => 'table table-striped'],
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'layoutOptions' => ['title' => $this->context->pageName],
    'showFooter' => true,
    'rowOptions' => ['class' => 'sortable-column']
]);

Pjax::end();
