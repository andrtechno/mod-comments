<?php

namespace panix\mod\comments\controllers\admin;

use Yii;
use panix\mod\comments\models\SettingsForm;

class SettingsController extends \panix\engine\controllers\AdminController {

    public $topButtons = false;

    public function actionIndex() {
        $this->pageName = Yii::t('app', 'SETTINGS');
        $this->breadcrumbs[] = [
            'label' => Yii::t('comments/default', 'MODULE_NAME'),
            'url' => ['/admin/comments']
        ];
        $this->breadcrumbs[] = $this->pageName;
        $model = new SettingsForm;
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $model->save();
            $this->refresh();
        }
        return $this->render('index', array('model' => $model));
    }

}
