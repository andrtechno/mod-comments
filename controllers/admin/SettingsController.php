<?php

class SettingsController extends AdminController {

    public $topButtons = false;

    public function actionIndex() {
        $this->pageName = Yii::t('app','SETTINGS');
        $this->breadcrumbs = array(
            Yii::t('CommentsModule.default','MODULE_NAME') => array('/admin/comments'),
            $this->pageName
                );
        $model = new SettingsCommentForm;
        if (Yii::app()->request->getParam('SettingsCommentForm')) {
            $model->attributes = Yii::app()->request->getParam('SettingsCommentForm');
            if ($model->validate()) {
                $model->save();
                $this->refresh();
            }
        }
        $this->render('index', array('model' => $model));
    }

}
