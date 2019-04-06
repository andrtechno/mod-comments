<?php

namespace panix\mod\comments\models;

use Yii;
use panix\engine\Html;
use panix\engine\db\ActiveRecord;

class Comments extends ActiveRecord {

    const MODULE_ID = 'comments';
    const STATUS_WAITING = 0;
    const STATUS_APPROVED = 1;
    const STATUS_SPAM = 2;

    public $defaultStatus;
    public $user_name;
    public $user_email;

    public static function find() {
        return new CommentsQuery(get_called_class());
    }

      public function init() {
     if (!Yii::$app->user->isGuest && Yii::$app->controller instanceof \panix\engine\controllers\WebController) {
         $this->user_name = Yii::$app->user->getDisplayName();
         $this->user_email = Yii::$app->user->email;
     }
      }
    /*
      public function withSwitch($status) {
      $this->getDbCriteria()->mergeWith(array(
      'condition' => 'switch=:st',
      'params' => array(':st' => $status)
      ));

      return $this;
      }
      public function getForm() {
      Yii::import('ext.bootstrap.selectinput.SelectInput');
      return new CMSForm(array(
      'attributes' => array(
      'id' => __CLASS__,
      'class' => 'form-horizontal',
      ),
      'showErrorSummary' => false,
      'elements' => array(
      'user_agent' => array('type' => 'none'),
      'ip_create' => array('type' => 'none'),
      'date_create' => array('type' => 'none'),
      'text' => array('type' => 'textarea'),
      'switch' => array(
      'type' => 'SelectInput',
      'data' => self::getStatuses()
      ),
      ),
      'buttons' => array(
      'submit' => array(
      'type' => 'submit',
      'class' => 'btn btn-success',
      'label' => ($this->isNewRecord) ? Yii::t('app', 'CREATE', 0) : Yii::t('app', 'SAVE')
      )
      )
      ), $this);
      }
     */
    public static function getStatuses() {
        return array(
            self::STATUS_WAITING => self::t('COMMENT_STATUS', self::STATUS_WAITING),
            self::STATUS_APPROVED => self::t('COMMENT_STATUS', self::STATUS_APPROVED),
            self::STATUS_SPAM => self::t('COMMENT_STATUS', self::STATUS_SPAM),
        );
    }

    public function getStatusTitle() {
        $statuses = self::getStatuses();
        return $statuses[$this->switch];
    }

    /**
     * Определяет таймаут управление комментарием
     * @return bool
     */
    public function controlTimeout() {
        $stime = strtotime($this->created_at) + Yii::$app->settings->get('comments', 'control_timeout');
        return (time() < $stime) ? true : false;
    }

    public function getEditLink() {
        $stime = strtotime($this->created_at) + Yii::$app->settings->get('comments', 'control_timeout');
        $userId = Yii::$app->user->id;
        if ($userId == $this->user_id || Yii::$app->user->isSuperuser) {
            return Html::a(Yii::t('app', 'UPDATE', 1), 'javascript:void(0)', array(
                        "onClick" => "$('#comment_" . $this->id . "').comment('update',{time:" . $stime . ", pk:" . $this->id . ", csrf:'" . Yii::$app->request->csrfToken . "'}); return false;",
                        'class' => 'btn btn-primary btn-sm',
                        'title' => Yii::t('app', 'UPDATE', 1)
            ));
        }
    }

    public function getDeleteLink() {
        $userId = Yii::$app->user->id;
        $stime = strtotime($this->created_at) + Yii::$app->settings->get('comments', 'control_timeout');
        if ($userId == $this->user_id || Yii::$app->user->isSuperuser) {
            return Html::a(Yii::t('app', 'DELETE'), 'javascript:void(0)', array(
                        "onClick" => "$('#comment_" . $this->id . "').comment('remove',{time:" . $stime . ", pk:" . $this->id . ", csrf:'" . Yii::$app->request->csrfToken . "'}); return false;",
                        'class' => 'btn btn-primary btn-sm',
                        'title' => Yii::t('app', 'DELETE')
            ));
        }
    }

    public function getUserWithAvatar($size = false) {
        $user = $this->user;
        if (isset($user->login)) {
            $avatar = Yii::$app->user->getAvatarUrl($size);
            return Html::a(Html::img($avatar) . $this->user->login, $user->getAdminEditUrl());
        } else {
            $avatar = Yii::$app->user->getAvatarUrl($size, true);
            return Html::img($avatar) . $this->user_name;
        }
    }

    public function getAvatarUrl($size = false) {
        $user = $this->user;
        if (isset($user->login)) {

            return Yii::$app->user->getAvatarUrl($size);
        } else {

            return Yii::$app->user->getAvatarUrl($size);
        }
    }

    public function getUserName() {
        $user = $this->user;
        if (isset($user->login)) {
            return Html::a($user->login, $user->getAdminEditUrl());
        } else {
            return $this->user_name;
        }
    }

    public function relations() {
        return array(
            'user' => array(self::BELONGS_TO, 'User', 'user_id'),
            'like' => array(self::HAS_ONE, 'Like', 'id'),
        );
    }

    public function scopes222() {
        $alias = $this->getTableAlias(true);
        return CMap::mergeArray(array(
                    'new' => array('condition' => $alias . '.switch=0'),
                        ), parent::scopes());
    }

    /**
     * @return string the associated database table name
     */
    public static function tableName() {
        return '{{%comments}}';
    }

    public function rules() {
        $rules = [];
        $rulesGuest = [];
        if (Yii::$app->user->isGuest) {
            $rulesGuest[] = [['user_name', 'user_email'], 'required'];
        } else {
            $rulesGuest = [];
        }
        $rules[] = [['user_agent', 'ip_create', 'user_name', 'user_email', 'text', 'owner_title', 'model'], 'string'];

        $rules[] = [['text', 'object_id', 'owner_title', 'model'], 'required'];
        //  $rules[] = ['date_create', 'date', 'format' => 'yyyy-M-d H:m:s'];
        return \yii\helpers\ArrayHelper::merge($rules, $rulesGuest);
    }

    /* public function behaviors() {
      return array(
      'like' => array(
      'class' => 'ext.like.LikeBehavior',
      'model' => 'mod.comments.models.Comments',
      'modelClass' => 'Comments',
      'nodeSave' => true
      ),
      'timezone' => array(
      'class' => 'app.behaviors.TimezoneBehavior',
      'attributes' => array('date_create'),
      )
      );
      } */

    /**
     * @return array customized attribute labels (name=>label)
     */
    public static function getCSort() {
        $sort = new CSort;
        $sort->defaultOrder = 't.created_at DESC, t.switch DESC';
        $sort->attributes = array(
            '*'
        );

        return $sort;
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return ActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    /* public function search($params=array()) {
      $criteria = new CDbCriteria;
      if(isset($params['model'])){
      $criteria->condition='model=:model AND object_id=:id';
      $criteria->params=array(':model'=>$params['model'],':id'=>$params['object_id']);
      }
      $criteria->compare('id', $this->id, true);
      $criteria->compare('text', $this->text, true);
      $criteria->compare('ip_create', $this->ip_create, true);

      return new ActiveDataProvider($this, array(
      'criteria' => $criteria,
      'sort' => self::getCSort()
      ));
      } */
}
