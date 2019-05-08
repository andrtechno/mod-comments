<?php

namespace panix\mod\comments\models;

use panix\engine\behaviors\nestedsets\NestedSetsBehavior;
use panix\engine\CMS;
use panix\mod\user\models\User;
use Yii;
use panix\engine\Html;
use panix\engine\db\ActiveRecord;
use yii\helpers\ArrayHelper;

class Comments extends ActiveRecord
{

    const MODULE_ID = 'comments';
    const STATUS_WAITING = 0;
    const STATUS_APPROVED = 1;
    const STATUS_SPAM = 2;

    public $defaultStatus;
    public $user_name;
    public $user_email;


    public function getGridColumns()
    {
        $columns = [];
        $columns['user_name'] = [
            'attribute' => 'user_name',
            'format' => 'html',
            'contentOptions' => ['class' => 'text-left'],
        ];
        $columns['text'] = [
            'attribute' => 'text',
            'format' => 'html',
            'contentOptions' => ['class' => 'text-left'],
        ];
        $columns['user_agent'] = [
            'attribute' => 'user_agent',
            'format' => 'html',
            'contentOptions' => ['class' => 'text-left'],
        ];

        $columns['ip_create'] = [
            'attribute' => 'ip_create',
            'format' => 'html',
            'contentOptions' => ['class' => 'text-left'],
        ];

        $columns['user_email'] = [
            'attribute' => 'user_email',
            'format' => 'html',
            'contentOptions' => ['class' => 'text-left'],
        ];
        $columns['owner_title'] = [
            'attribute' => 'owner_title',
            'format' => 'html',
            'contentOptions' => ['class' => 'text-left'],
        ];
        $columns['handlerClass'] = [
            'attribute' => 'handlerClass',
            'format' => 'html',
            'contentOptions' => ['class' => 'text-left'],
        ];
        $columns['object_id'] = [
            'attribute' => 'object_id',
            'format' => 'html',
            'contentOptions' => ['class' => 'text-left'],
        ];

        $columns['created_at'] = [
            'attribute' => 'created_at',
            'class' => 'panix\engine\grid\columns\jui\DatepickerColumn',
            'format' => 'raw',
            'headerOptions' => ['style' => 'width:150px'],
            'contentOptions' => ['class' => 'text-center'],
            'value' => function ($model) {
                if ($model->created_at) {
                    $html = Html::beginTag('span', ['class' => 'bootstrap-tooltip', 'title' => Yii::t('app', 'IN') . ' ' . CMS::date($model->created_at)]);
                    $html .= CMS::date($model->created_at);
                    $html .= Html::endTag('span');
                    return $html;
                }
                return null;
            }
        ];
        $columns['updated_at'] = [
            'attribute' => 'updated_at',
            'class' => 'panix\engine\grid\columns\jui\DatepickerColumn',
            'format' => 'raw',
            'headerOptions' => ['style' => 'width:150px', 'class' => 'text-center'],
            'contentOptions' => ['class' => 'text-center'],
            'value' => function ($model) {
                if ($model->updated_at) {
                    $html = Html::beginTag('span', ['class' => 'bootstrap-tooltip', 'title' => Yii::t('app', 'IN') . ' ' . CMS::date($model->updated_at)]);
                    $html .= CMS::date($model->updated_at);
                    $html .= Html::endTag('span');
                    return $html;
                }
                return null;
            }
        ];


        $columns['DEFAULT_CONTROL'] = [
            'class' => 'panix\engine\grid\columns\ActionColumn',
        ];
        $columns['DEFAULT_COLUMNS'] = [
            [
                'class' => \panix\engine\grid\sortable\Column::class,
                'url' => ['/shop/product/sortable']
            ],
            [
                'class' => 'panix\engine\grid\columns\CheckboxColumn',
            ]
        ];

        return $columns;
    }


    public static function find()
    {
        return new CommentsQuery(get_called_class());
    }

    public function init()
    {
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
    public static function getStatuses()
    {
        return [
            self::STATUS_WAITING => self::t('COMMENT_STATUS_WAIT'),
            self::STATUS_APPROVED => self::t('COMMENT_STATUS_CONFIRMED'),
            self::STATUS_SPAM => self::t('COMMENT_STATUS_SPAM'),
        ];
    }

    public function getStatusTitle()
    {
        $statuses = self::getStatuses();
        return $statuses[$this->switch];
    }



    public function getUserWithAvatar($size = false)
    {
        $user = $this->user;
        if (isset($user->login)) {
            $avatar = Yii::$app->user->getAvatarUrl($size);
            return Html::a(Html::img($avatar) . $this->user->login, $user->getAdminEditUrl());
        } else {
            $avatar = Yii::$app->user->getAvatarUrl($size, true);
            return Html::img($avatar) . $this->user_name;
        }
    }

    public function getAvatarUrl($size = false)
    {
        $user = $this->user;
        if (isset($user->login)) {

            return Yii::$app->user->getAvatarUrl($size);
        } else {

            return Yii::$app->user->getAvatarUrl($size);
        }
    }

    public function getUserName()
    {
        $user = $this->user;
        if (isset($user->login)) {
            return Html::a($user->login, $user->getAdminEditUrl());
        } else {
            return $this->user_name;
        }
    }


    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    public function getLike()
    {
        return $this->hasOne(Like::class, ['id']);
    }

    public function behaviors()
    {
        return ArrayHelper::merge([
            'tree' => [
                'class' => NestedSetsBehavior::class,
                'hasManyRoots'=>true
            ],
           /* 'like' => [
                'class' => 'ext.like.LikeBehavior',
                'model' => 'mod.comments.models.Comments',
                'modelClass' => 'Comments',
                'nodeSave' => true
            ],*/
        ], parent::behaviors());
    }

    /**
     * @return string the associated database table name
     */
    public static function tableName()
    {
        return '{{%comments}}';
    }

    public function rules()
    {
        $rules = [];
        $rulesGuest = [];
        if (Yii::$app->user->isGuest) {
            $rulesGuest[] = [['user_name', 'user_email'], 'required'];
        } else {
            $rulesGuest = [];
        }
        $rules[] = [['user_agent', 'ip_create', 'user_name', 'user_email', 'text', 'owner_title', 'handlerClass'], 'string'];

        $rules[] = [['text', 'object_id', 'owner_title', 'handlerClass'], 'required'];
        //  $rules[] = ['date_create', 'date', 'format' => 'yyyy-M-d H:m:s'];
        return \yii\helpers\ArrayHelper::merge($rules, $rulesGuest);
    }

    /**
     * @return bool
     */
    public function hasAccessControl()
    {
        $conf = (int) Yii::$app->settings->get('comments', 'control_timeout');
        if((time() - $conf <= $this->created_at) && $this->user_id == Yii::$app->user->id){
            return true;
        }
        return false;
    }


}
