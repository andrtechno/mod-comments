<?php

namespace panix\mod\comments\controllers\admin;

use Yii;
use panix\mod\comments\models\SettingsForm;
use panix\engine\controllers\AdminController;

class SettingsController extends AdminController {

    public $topButtons = false;

    public function actionIndex() {
        $this->pageName = Yii::t('app/default', 'SETTINGS');
        $this->breadcrumbs[] = [
            'label' => Yii::t('comments/default', 'MODULE_NAME'),
            'url' => ['/admin/comments']
        ];
        $this->breadcrumbs[] = $this->pageName;
        $model = new SettingsForm;
        if ($model->load(Yii::$app->request->post())) {
            if ($model->validate()) {
                $model->save();
                Yii::$app->session->setFlash("success", Yii::t('app/default', 'SUCCESS_UPDATE'));
            }
            return $this->refresh();
        }
        return $this->render('index', ['model' => $model]);
    }

}
