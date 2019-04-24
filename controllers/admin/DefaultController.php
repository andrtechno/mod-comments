<?php
namespace panix\mod\comments\controllers\admin;
use Yii;
use panix\mod\comments\models\CommentsSearch;
class DefaultController extends \panix\engine\controllers\AdminController {



    public function actions() {
        return array(
            'switch' => array(
                'class' => 'ext.adminList.actions.SwitchAction',
            ),
            'delete' => array(
                'class' => 'ext.adminList.actions.DeleteAction',
            ),
        );
    }

    public function actionIndex() {

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
     * @throws CHttpException
     */
    public function actionUpdate($id) {

        $model = Comments::findModel($id,Yii::t('comments/default', 'NO_FOUND_COMMENT'));


        $this->pageName = Yii::t('comments/default', 'EDITED');

        if (Yii::$app->request->isPostRequest) {
            $model->attributes = $_POST['Comments'];
            if ($model->validate()) {

                $model->saveNode();
                $this->redirect(array('index'));
            }
        }

        $this->render('update', array('model' => $model));
    }

    public function actionUpdateStatus() {
        $ids = Yii::$app->request->post('ids');
        $switch = Yii::$app->request->post('switch');
        $models = Comments::model()->findAllByPk($ids);

        if (!array_key_exists($switch, Comments::getStatuses()))
            throw new CHttpException(404, Yii::t('comments/default', 'ERROR_UPDATE_STATUS'));

        if (!empty($models)) {
            foreach ($models as $comment) {
                $comment->switch = $switch;
                $comment->save();
            }
        }

        echo Yii::t('comments/default', 'SUCCESS_UPDATE_STATUS');
    }

}
