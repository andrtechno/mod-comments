<?php

class Module extends \panix\engine\WebModule {

    public $countComments;
    public $icon = 'comments';
    public function init() {
        if (Yii::$app->db->schema->getTable(Comments::tableName())) {
            $this->countComments = Comments::model()->withSwitch(Comments::STATUS_WAITING)->count();
        }
    }

    public function processRequest($model) {

        $comment = new Comments;
        $request = Yii::app()->request;
        $pkAttr = $model->getObjectPkAttribute();
        if ($request->isPostRequest && $request->isAjaxRequest) {

            $comment->attributes = $request->getPost('Comments');
            $comment->model = $model->getModelName();
            $comment->owner_title = $model->getOwnerTitle();
            $comment->object_id = $model->$pkAttr;
            if ($comment->validate()) {
                $comment->save();
                echo CJSON::encode(array('success' => 'OK'));
                Yii::app()->end();
                Yii::app()->session['caf'] = time();
            }
        }
        return $comment;
    }


        public $routes=[
            'comments/edit' => '/comments/default/edit',
            'comments/reply/<id:(\d+)>' => '/comments/default/reply',
            'comments/reply_submit/' => '/comments/default/reply_submit',
            'comments/delete/<id:(\d+)>' => '/comments/default/delete',
            //'/comments/edit/save' => '/comments/default/edit',
            'comments/create' => '/comments/default/create',
            'comments/auth' => '/comments/default/authProvider',
            'comments/auth/<provide>' => '/comments/default/auth',
            'rate/<type:(up|down)>/<object_id:(\d+)>' => '/comments/default/rate',
        ];



    public function getVersion() {
        return '1.0 Lite';
    }

    public function getAdminMenu() {
        Yii::import('mod.comments.models.Comments');
        $c = Yii::app()->controller->id;
        return array(
            'system' => array(
                'items' => array(
                    array(
                        'label' => $this->name,
                        'url' => $this->adminHomeUrl,
                        'icon' => Html::icon($this->icon),
                        'active' => ($c == 'admin/default') ? true : false,
                        'visible' => Yii::app()->user->openAccess(array('Comments.Default.*', 'Comments.Default.Index')),
                        'count' => $this->countComments
                    ),
                ),
            ),
        );
    }

    public function getAdminSidebarMenu() {
        $c = Yii::app()->controller->id;
        return array(
            $this->adminMenu['system']['items'][0],
            array(
                'label' => Yii::t('app', 'SETTINGS'),
                'url' => array('/admin/comments/settings'),
                'active' => ($c == 'admin/settings') ? true : false,
                'icon' => Html::icon('icon-settings'),
                'visible' => Yii::app()->user->openAccess(array('Comments.Settings.*', 'Comments.Settings.Index')),
            )
        );
    }

}
