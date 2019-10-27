<?php

namespace panix\mod\comments\controllers;

use Yii;
use yii\helpers\Json;
use panix\engine\CMS;
use panix\mod\comments\models\Comments;
use yii\web\Response;
use panix\engine\controllers\WebController;
use yii\widgets\ActiveForm;

class DefaultController extends WebController
{

    public $defaultAction = 'admin';

    public function filters()
    {
        return array(
            //   'accessControl', // perform access control for CRUD operations
            //  'ajaxOnly + PostComment, Delete, Approve, Edit',
        );
    }

    public function actions()
    {
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
    public function accessRules2()
    {
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

    public function actionRate($type, $object_id)
    {
        // $model = Yii::import("mod.comments.models.NestedComments");
        $like = new Like;
        $like->model = 'mod.comments.models.Comments';
        $like->rate = $type;
        $like->object_id = $object_id;
        if ($like->validate()) {
            $modelClass = Yii::import("mod.comments.models.Comments");
            $model = $modelClass::model()->findByPk($object_id);
            if ($type == 'up') {
                $model->like += 1;
            } elseif ($type == 'down') {
                $model->like -= 1;
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

    public function actionUpdate()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $model = Comments::findModel(Yii::$app->request->get('id'));
        $result = [];
        //if (Yii::$app->request->isAjax) {
        if ($model->hasAccessControl() || Yii::$app->user->can('admin')) {
            if (isset($_POST['Comments'])) {
                $model->attributes = $_POST['Comments'];
                if ($model->validate()) {
                    $model->save();
                    $result = [
                        'status' => 'success',
                        'message' => 'Комментарий успешно отредактирован',
                        'response' => nl2br(CMS::bb_decode(Html::text($model->text)))
                    ];
                } else {
                    $result = [
                        'status' => 'error',
                        'response' => $model->getErrors()
                    ];
                }

            } else {
                $result = [
                    'status' => 'success',
                    'result' => $this->renderPartial('_edit_form', ['model' => $model])
                ];
                //return $this->renderPartial('_edit_form', ['model' => $model]);
            }
        } else {
            $result['status'] = 'error';
            $result['message'] = 'Access denied 1';
        }
        // } else {
        //     $result['status'] = 'error';
        //     $result['message'] = 'Access denied 2';
        // }
        return $result;
    }

    /**
     * @param $id
     * @return array
     */
    public function actionDelete($id)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $model = Comments::findModel($id);
        $result = [];
        $result['status'] = 'error';
        if (Yii::$app->request->isAjax) {
            if ($model->hasAccessControl() || Yii::$app->user->can('admin')) {
                if ($model->deleteNode()) {
                    $result['status'] = 'success';
                    $result['message'] = 'Комментарий удален.';
                } else {
                    $result['message'] = 'Ошибка удаление.';
                }
            } else {
                $result['message'] = 'Access denied';
            }
        } else {
            $result['message'] = 'Access denied';
        }
        return $result;
    }

    /**
     * Deletes a particular model.
     * @param integer $id the ID of the model to be deleted
     *
     * public function actionDelete($id) {
     * $model = $this->loadModel($id);
     * $result = array('deletedID' => $id);
     * if ($model->delete()) {
     * $result['code'] = 'success';
     * $result['flash_message'] = 'Комментарий удален.';
     * } else {
     * $result['code'] = 'fail';
     * $result['flash_message'] = 'Ошибка удаление.';
     * }
     * echo CJSON::encode($result);
     * } */


    /**
     * Approves a particular model.
     *
     * @param int $id The ID of the model to be approve
     * @return array
     */
    public function actionApprove($id)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        // we only allow deletion via POST request
        $result = array('approvedID' => $id);
        if (Comments::findModel($id)->setApproved())
            $result['code'] = 'success';
        else
            $result['code'] = 'fail';
        return $result;
    }

    /**
     * @param $id
     * @return array|string
     */
    public function actionReply($id)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $result = [];
        $result['status'] = 'error'; //default status
        $reply = Comments::findModel($id);
//	$this->enableCsrfValidation = false;

        $comment = new Comments();
        $comment->handler_class = $reply->handler_class;
        $comment->object_id = $reply->object_id;
        $comment->owner_title = $reply->owner_title;
        // if (Yii::$app->request->isAjax) {
        if (Yii::$app->request->isPost) {
            if ($comment->load(Yii::$app->request->post()) && $comment->validate()) {
                if ($comment->appendTo($reply)) {
                    $result['status'] = 'success';
                    $result['message'] = 'OK';
                    $reply->sendNotifyReply($comment);
                }
            } else {
                return ActiveForm::validate($comment);
                //$result['errors'] = ActiveForm::validate($comment);

            }

            return $result;
        } else {
            Yii::$app->response->format = Response::FORMAT_HTML;
            return $this->renderPartial('_reply_form', ['model' => $comment, 'reply' => $reply]);
        }
        //}

    }

    /**
     * GET open form
     * POST send form
     *
     * @return array
     */
    public function actionAdd()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $id = Yii::$app->request->get('id');
        $comment = new Comments;
        $reply = Comments::findOne($id);

        $request = Yii::$app->request;
        $json = [];
        // if ($request->isPost && $request->isAjax) {

        $comment->attributes = $request->post('Comments');
        if ($comment->validate()) {
            if (Yii::$app->user->can('admin')) {
                $comment->switch = 1;
            }

            $comment->saveNode();


            //
            Yii::$app->session['caf'] = time();

            $json = [
                'success' => true,
                'message' => Yii::t('comments/default', $comment->switch ? 'SUCCESS_ADD' : 'SUCCESS_ADD_MODERATION')
            ];

        } else {

            $json = [
                'success' => false,
                'message' => 'Error',
                'errors' => $comment->getErrors()
            ];
        }

        // }

        return $json;
    }

}
