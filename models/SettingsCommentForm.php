<?php

class SettingsCommentForm extends FormSettingsModel {

    public $pagenum;
    public $allow_add;
    public $allow_view;
    public $flood_time;
    public $control_timeout;

    public function getForm() {
        Yii::import('ext.bootstrap.selectinput.SelectInput');
        return new CMSForm(array(
            'attributes' => array(
                'id' => __CLASS__,
                'class' => 'form-horizontal',
            ),
            'showErrorSummary' => true,
            'elements' => array(
                'pagenum' => array('type' => 'text'),
                'flood_time' => array('type' => 'text'),
                'control_timeout' => array('type' => 'text'),
                'allow_add' => array(
                    'type' => 'SelectInput',
                    'data' => Yii::app()->access->dataList(),
                    'htmlOptions' => array(
                        'class' => 'form-control'
                    )
                ),
                'allow_view' => array(
                    'type' => 'SelectInput',
                    'data' => Yii::app()->access->dataList(),
                    'htmlOptions' => array(
                        'class' => 'form-control'
                    )
                )
            ),
            'buttons' => array(
                'submit' => array(
                    'type' => 'submit',
                    'class' => 'btn btn-success',
                    'label' => Yii::t('app', 'SAVE')
                )
            )
                ), $this);
    }

    public function init() {
        parent::init();
        $this->control_timeout = $this->control_timeout / 60;
    }

    public function rules() {
        return array(
            array('pagenum, flood_time, allow_add, allow_view, control_timeout', 'required'),
            //array('bad_name, bad_email', 'length', 'max' => 255),
            array('pagenum', 'numerical', 'integerOnly' => true),
        );
    }

    public function save($message = true) {
        $this->control_timeout = $_POST['SettingsCommentForm']['control_timeout'] * 60;
        Yii::app()->settings->set('comments', $this->attributes);
        parent::save($message);
    }

}
