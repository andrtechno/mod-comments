<?php
namespace panix\mod\comments\controllers;
use Yii;
use yii\helpers\Json;
use panix\engine\CMS;
use panix\mod\comments\models\Comments;
class DefaultController extends \panix\engine\controllers\WebController {

    public $defaultAction = 'admin';

    public function filters() {
        return array(
                //   'accessControl', // perform access control for CRUD operations
                //  'ajaxOnly + PostComment, Delete, Approve, Edit',
        );
    }

    public function actions() {
        return array(
            'captcha' => array(
                'class' => 'CCaptchaAction',
                'backColor' => 0xFFFFFF,
            ),
        );
    }

    /**
     * Specifies the access control rules.
     * This method is used by the 'accessControl' filter.
     * @return array access control rules
     */
    public function accessRules() {
        return array(
            array('allow',
                'actions' => array('postComment', 'captcha', 'authProvider', 'auth'),
                'users' => array('*'),
            ),
            array('allow',
                'actions' => array('admin', 'delete', 'approve', 'edit'),
                'users' => array('admin'),
            ),
            array('allow',
                'actions' => array('edit', 'delete', 'create', 'reply', 'reply_submit', 'rate'),
                'users' => array('@'),
            ),
            array('deny', // deny all users
                'users' => array('*'),
            ),
        );
    }

    public function actionRate($type, $object_id) {
        // $model = Yii::import("mod.comments.models.NestedComments");
        $like = new Like;
        $like->model = 'mod.comments.models.Comments';
        $like->rate = $type;
        $like->object_id = $object_id;
        if ($like->validate()) {
            $modelClass = Yii::import("mod.comments.models.Comments");
            $model = $modelClass::model()->findByPk($object_id);
            if ($type == 'up') {
                $model->like+=1;
            } elseif ($type == 'down') {
                $model->like-=1;
            }
            $model->saveNode();

            $like->save(false, false);
            $json = array(
                'num' => $model->getLikes()
            );
        } else {
            $json = array('error' => 'error validate');
        }
        echo CJSON::encode($json);
    }

    public function actionEdit() {
        $model = Comments::model()->findByPk((int) $_POST['_id']);
        // Yii::$app->request->enableCsrfValidation=false;
        if ($model->controlTimeout()) {
            if (Yii::$app->request->isAjaxRequest) {
                if ($model->user_id == Yii::$app->user->id || Yii::$app->user->getIsSuperuser()) {
                    if (isset($_POST['Comments'])) {
                        $model->attributes = $_POST['Comments'];
                        if ($model->validate()) {
                            $model->save();
                            $data = array(
                                'code' => 'success',
                                'flash_message' => 'Комментарий успешно отредактирован',
                                'response' => nl2br(CMS::bb_decode(Html::text($model->text)))
                            );
                        } else {
                            $data = array(
                                'code' => 'fail',
                                'response' => $model->getErrors()
                            );
                        }
                        echo CJSON::encode($data);
                    } else {
                        $this->render('_edit_form', array('model' => $model));
                    }
                } else {
                    die('Access denie ' . $model->user_id . ' - ' . Yii::$app->user->id);
                }
            } else {
                die('Access denie 2');
            }
        } else {
            $data = array(
                'code' => 'fail',
                'response' => 'Время редактирование завершено!'
            );
            echo CJSON::encode($data);
        }
    }

    /**
     * Deletes a particular model.
     * @param integer $id the ID of the model to be deleted
     */
    public function actionDelete($id) {
        $model = $this->loadModel($id);
        if ($model->controlTimeout()) {
            $result = array('deletedID' => $id);
            if ($model->delete()) {
                $result['code'] = 'success';
                $result['flash_message'] = 'Комментарий удален.';
            } else {
                $result['code'] = 'fail';
                $result['flash_message'] = 'Ошибка удаление.';
            }
        } else {
            $result['code'] = 'fail';
            $result['flash_message'] = 'Таймаут';
        }
        echo CJSON::encode($result);
    }

    /**
     * Deletes a particular model.
     * @param integer $id the ID of the model to be deleted

      public function actionDelete($id) {
      $model = $this->loadModel($id);
      $result = array('deletedID' => $id);
      if ($model->delete()) {
      $result['code'] = 'success';
      $result['flash_message'] = 'Комментарий удален.';
      } else {
      $result['code'] = 'fail';
      $result['flash_message'] = 'Ошибка удаление.';
      }
      echo CJSON::encode($result);
      } */

    /**
     * Approves a particular model.
     * @param integer $id the ID of the model to be approve
     */
    public function actionApprove($id) {
        // we only allow deletion via POST request
        $result = array('approvedID' => $id);
        if ($this->loadModel($id)->setApproved())
            $result['code'] = 'success';
        else
            $result['code'] = 'fail';
        echo CJSON::encode($result);
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer the ID of the model to be loaded
     */
    public function loadModel($id) {
        $model = Comments::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404);
        return $model;
    }

    public function actionAdd() {
        $comment = new Comments;
        $request = Yii::$app->request;
        if ($request->isPost && $request->isAjax) {
            $comment->attributes = $request->post('Comments');
            if ($comment->validate()) {
                if(Yii::$app->user->can('admin')){
                    $comment->switch = 1;
                }
                $comment->save();
                echo Json::encode(array(
                    'success' => true,
                    'grid_update'=>(Yii::$app->user->can('admin'))?true:false,
                    'message' => Yii::t('comments/default', 'SUCCESS_ADD', $comment->switch)
                ));
                Yii::$app->session['caf'] = CMS::time();
            } else {
                echo Json::encode(array(
                    'success' => false,
                    'grid_update'=>false,
                    'message' => $comment->getError('text')
                ));
            }
            die;
        }
    }

}
