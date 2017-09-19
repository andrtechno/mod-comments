<?php

namespace panix\mod\comments;

use Yii;
use panix\engine\Html;
use panix\mod\comments\models\Comments;
class Module extends \panix\engine\WebModule {

    public $countComments;
    public $icon = 'comments';

    public function init() {
        // if (Yii::$app->db->schema->getTable(Comments::tableName())) {
        //     $this->countComments = Comments::model()->withSwitch(Comments::STATUS_WAITING)->count();
        // }
        parent::init();
    }

    public function processRequest($model) {

        $comment = new Comments;
        $request = Yii::$app->request;
        $pkAttr = $model->getObjectPkAttribute();
        if ($request->isPost && $request->isAjax) {

            $comment->attributes = $request->post('Comments');
            $comment->model = $model->getModelName();
            $comment->owner_title = $model->getOwnerTitle();
            $comment->object_id = $model->$pkAttr;
            if ($comment->validate()) {
                $comment->save();
                echo \yii\helpers\Json::encode(array('success' => 'OK'));
                
                Yii::$app->session['caf'] = time();
                die;
            }
        }
        return $comment;
    }

    public $routes = [
        'comments/edit' => 'comments/default/edit',
        'comments/reply/<id:(\d+)>' => 'comments/default/reply',
        'comments/reply_submit/' => 'comments/default/reply_submit',
        'comments/delete/<id:(\d+)>' => 'comments/default/delete',
        //'/comments/edit/save' => '/comments/default/edit',
        'comments/add' => 'comments/default/add',
        'comments/auth' => 'comments/default/authProvider',
        'comments/auth/<provide>' => 'comments/default/auth',
        'rate/<type:(up|down)>/<object_id:(\d+)>' => 'comments/default/rate',
    ];


    public function getInfo() {
        return [
            'label' => Yii::t('comments/default', 'MODULE_NAME'),
            'author' => 'andrew.panix@gmail.com',
            'version' => '1.0',
            'icon' => $this->icon,
            'description' => Yii::t('comments/default', 'MODULE_DESC'),
            'url' => ['/admin/comments'],
        ];
    }

    public function getAdminMenu() {
        return [
            'system' => [
                'items' => [
                    [
                        'label' => Yii::t('comments/default', 'MODULE_NAME'),
                        'url' => ['/admin/comments'],
                        'icon' => $this->icon,
                        'count' => $this->countComments
                    ],
                ],
            ],
        ];
    }

    /*public function getAdminSidebarMenu() {
        $c = Yii::$app->controller->id;
        return array(
            $this->adminMenu['system']['items'][0],
            array(
                'label' => Yii::t('app', 'SETTINGS'),
                'url' => array('/admin/comments/settings'),
                'active' => ($c == 'admin/settings') ? true : false,
                'icon' => Html::icon('icon-settings'),
            )
        );
    }*/

}
