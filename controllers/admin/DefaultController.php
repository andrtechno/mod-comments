<?php

namespace panix\mod\comments\controllers\admin;

use Yii;
use panix\mod\comments\models\Comments;
use panix\mod\comments\models\CommentsSearch;
use yii\web\NotFoundHttpException;

class DefaultController extends \panix\engine\controllers\AdminController
{


    public function actions()
    {
        return array(
            'switch' => array(
                'class' => 'ext.adminList.actions.SwitchAction',
            ),
            'delete' => array(
                'class' => 'ext.adminList.actions.DeleteAction',
            ),
        );
    }

    public function actionIndex()
    {

        // Yii::$app->clientScript->registerScriptFile($this->module->assetsUrl . '/admin/comments.index.js');

        $this->pageName = Yii::t('comments/default', 'MODULE_NAME');

        $this->breadcrumbs = array($this->pageName);


        $searchModel = new CommentsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

    /**
     * Update comment
     * @param $id
     * @return string
     */
    public function actionUpdate($id)
    {

        $model = Comments::findModel($id, Yii::t('comments/default', 'NO_FOUND_COMMENT'));


        $this->pageName = Yii::t('comments/default', 'EDITED');

        if (Yii::$app->request->isPost) {
            $model->attributes = $_POST['Comments'];
            if ($model->validate()) {

                $model->save();

                $redirect = (isset($post['redirect'])) ? $post['redirect'] : Yii::$app->request->url;
                if (!Yii::$app->request->isAjax)
                    return Yii::$app->getResponse()->redirect($redirect);
            }
        }

        return $this->render('update', array('model' => $model));
    }

    public function actionUpdateStatus()
    {
        $ids = Yii::$app->request->post('ids');
        $switch = Yii::$app->request->post('switch');
        $models = Comments::findAll($ids);

        if (!array_key_exists($switch, Comments::getStatuses()))
            throw new NotFoundHttpException(Yii::t('comments/default', 'ERROR_UPDATE_STATUS'));

        if (!empty($models)) {
            foreach ($models as $comment) {
                $comment->switch = $switch;
                $comment->save();
            }
        }

        echo Yii::t('comments/default', 'SUCCESS_UPDATE_STATUS');
    }

}
