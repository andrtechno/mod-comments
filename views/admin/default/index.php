
<?php

$this->widget('ext.adminList.GridView', array(
    'dataProvider' => $dataProvider,
    'autoColumns' => false,
    'name' => $this->pageName,
    'filter' => $model,
    'rowCssClassExpression' => function($row, $data) {
        if ($data->switch == Comments::STATUS_WAITING) {
            return 'success';
        } elseif ($data->switch == Comments::STATUS_SPAM) {
            return 'danger';
        }
    },
    'customActions' => array(
        array(
            'label' => Yii::t('CommentsModule.Comments', 'COMMENT_STATUS', 0),
            'url' => '#',
            'linkOptions' => array(
                'onClick' => 'return setCommentsStatus(0, this);',
            )
        ),
        array(
            'label' => Yii::t('CommentsModule.Comments', 'COMMENT_STATUS', 1),
            'url' => '#',
            'linkOptions' => array(
                'onClick' => 'return setCommentsStatus(1, this);',
            )
        ),
        array(
            'label' => Yii::t('CommentsModule.Comments', 'COMMENT_STATUS', 2),
            'url' => '#',
            'linkOptions' => array(
                'onClick' => 'return setCommentsStatus(2, this);',
            )
        ),
    ),
    'columns' => array(
        array('class' => 'CheckBoxColumn'),
        array(
            'name' => 'text',
            'value' => 'CMS::truncate("$data->text", 100)',
            'htmlOptions' => array('class' => 'text-left')
        ),
        array(
            'name' => 'user_name',
            'type' => 'html',
            'value' => '$data->getUserWithAvatar("25x25")',
            'htmlOptions' => array('class' => 'text-left')
        ),
        array(
            'name' => 'switch',
            'filter' => Comments::getStatuses(),
            'value' => '$data->statusTitle',
        ),
        array(
            'name' => 'owner_title',
            'filter' => false
        ),
        array(
            'name' => 'date_create',
            'value' => 'CMS::date("$data->date_create")'
        ),
        array(
            'type' => 'raw',
            'name' => 'ip_create',
            'value' => 'CMS::ip("$data->ip_create", 1)'
        ),
        array(
            'class' => 'ButtonColumn',
            'template' => '{switch}{update}{delete}',
        ),
    ),
));
?>

