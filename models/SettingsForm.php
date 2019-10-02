<?php

namespace panix\mod\comments\models;

use panix\engine\SettingsModel;

class SettingsForm extends SettingsModel
{

    protected $module = 'comments';
    public static $category = 'comments';
    public $pagenum;
    public $allow_add;
    public $allow_view;
    public $flood_time;
    public $control_timeout;

    public function init()
    {
        parent::init();
        $this->control_timeout = $this->control_timeout / 60;
    }

    public function rules()
    {
        return [
            [['pagenum', 'flood_time', 'allow_add', 'allow_view', 'control_timeout'], 'required'],
            //array('bad_name, bad_email', 'length', 'max' => 255),
            [['pagenum', 'control_timeout'], 'number'],
        ];
    }

    public function save()
    {
        $this->control_timeout = $_POST['SettingsForm']['control_timeout'] * 60;
        parent::save();
    }

    public static function defaultSettings()
    {
        return [
            'pagenum' => 10,
            'flood_time' => 5,
            'allow_add' => 1,
            'allow_view' => 1,
            'control_timeout' => 5 * 60
        ];
    }
}
