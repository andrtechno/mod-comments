<?php

namespace panix\mod\comments\widgets\comment;

use Yii;
use panix\mod\comments\models\Comments;
use panix\engine\data\ActiveDataProvider;
use panix\engine\data\Widget;

/**
 * Class CommentWidget
 * @package panix\mod\comments\widgets\comment
 */
class CommentWidget extends Widget
{

    public $model;

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
                'handler_hash' => $this->model->getHandlerHash(),
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
            'handler_class' => $this->model->getHandlerClass()
        ));


    }

}