<?php

namespace panix\mod\comments\models;

use Yii;

class SettingsForm extends \panix\engine\SettingsModel {

    protected $module = 'comments';
    public $pagenum;
    public $allow_add;
    public $allow_view;
    public $flood_time;
    public $control_timeout;

    public function init() {
        parent::init();
        $this->control_timeout = $this->control_timeout / 60;
    }

    public function rules() {
        return [
            [['pagenum', 'flood_time', 'allow_add', 'allow_view', 'control_timeout'], 'required'],
            //array('bad_name, bad_email', 'length', 'max' => 255),
            ['pagenum', 'number'],
        ];
    }

    public function save($message = true) {
        $this->control_timeout = $_POST['SettingsForm']['control_timeout'] * 60;
        Yii::$app->settings->set('comments', $this->attributes);
        parent::save($message);
    }

}
