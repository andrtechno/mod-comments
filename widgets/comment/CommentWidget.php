<?php
namespace panix\mod\comments\widgets\comment;
use Yii;
use panix\mod\comments\models\Comments;
use panix\engine\data\ActiveDataProvider;
class CommentWidget extends \panix\engine\data\Widget {

    public $model;

    public function init() {

     //   $this->registerAssets();
    }

    public function run() {

        $module = Yii::$app->getModule('comments');

        $comment = $module->processRequest($this->model);
        $currentUrl = 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        $config = Yii::$app->settings->get('comments');

       /* $criteria = new CDbCriteria;
        $criteria->condition = '`t`.`model`=:class AND object_id=:pk';
        $criteria->scopes = array('published');
        $criteria->order = '`t`.`date_create` DESC';
        $criteria->params = array(
            ':class' => $this->model->getModelName(),
            ':pk' => $this->model->id,
        );*/

        
        $query = Comments::find()
                ->published()
                ->orderBy('date_create DESC')
                ->where(['model'=>$this->model->getModelName(),'object_id'=>$this->model->id]);
        
        $dataProvider = new ActiveDataProvider([
                    'query' => $query,
                    //'pagination' => array(
                       // 'pageVar' => 'comment_page',
                       // 'pageSize' => $config['pagenum']
                    //)
                ]);

        $obj_id = $this->model->getObjectPkAttribute();
       // $this->render((Yii::$app->controller instanceof AdminController)?'comment_list_backend':'comment_list', array('dataProvider' => $dataProvider));

        return $this->render('comment_form', array(
            'comment' => $comment,
            'currentUrl' => $currentUrl,
            'object_id'=>$this->model->$obj_id,
            'owner_title'=>$this->model->getOwnerTitle(),
            'model'=>$this->model->getModelName()
            ));

        
    }

    public function registerAssets() {
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