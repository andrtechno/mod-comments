<?php

namespace panix\mod\comments\widgets\comment;

use panix\engine\controllers\AdminController;
use Yii;
use panix\mod\comments\models\Comments;
use panix\engine\data\ActiveDataProvider;

class CommentWidget extends \panix\engine\data\Widget
{

    public $model;

    public function init()
    {

        //   $this->registerAssets();
    }

    public function run()
    {

        $module = Yii::$app->getModule('comments');

        $comment = $module->processRequest($this->model);
        $currentUrl = 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        $config = Yii::$app->settings->get('comments');

        $query = Comments::find()
            ->published()
            ->orderBy(['id' => SORT_DESC])
            ->where([
                'depth'=>1,
                'handlerClass' => $this->model->getHandlerClass(),
                'object_id' => $this->model->id
            ]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                // 'pageVar' => 'comment_page',
                'pageSize' => $config->pagenum
            ]
        ]);

        $obj_id = $this->model->getObjectPkAttribute();
        // $this->render((Yii::$app->controller instanceof AdminController)?'comment_list_backend':'comment_list', array('dataProvider' => $dataProvider));

        return $this->render('comment_form', array(
            'dataProvider' => $dataProvider,
            'comment' => $comment,
            'currentUrl' => $currentUrl,
            'model' => $this->model,
            'object_id' => $this->model->{$obj_id},
            'owner_title' => $this->model->getOwnerTitle(),
            'handlerClass' => $this->model->getHandlerClass()
        ));


    }

    public function registerAssets()
    {
        $assets = dirname(__FILE__) . '/assets';
        $baseUrl = Yii::$app->assetManager->publish($assets, false, -1, YII_DEBUG);
        $css = (Yii::$app->controller instanceof AdminController) ? 'admin_comments.css' : 'comments.css';
        if (is_dir($assets)) {
            Yii::$app->clientScript->registerScriptFile($baseUrl . '/js/jquery.session.js', CClientScript::POS_HEAD);
            Yii::$app->clientScript->registerScriptFile($baseUrl . '/js/comment.js', CClientScript::POS_HEAD);
            Yii::$app->clientScript->registerCssFile($baseUrl . '/css/' . $css);
        } else {
            throw new Exception(__CLASS__ . ' - Error: Couldn\'t find assets to publish.');
        }
    }

}