<?php

namespace panix\mod\comments\models;

use Yii;
use yii\helpers\ArrayHelper;
use panix\engine\behaviors\nestedsets\NestedSetsBehavior;
use panix\engine\CMS;
use panix\mod\user\models\User;
use panix\engine\Html;
use panix\engine\db\ActiveRecord;

/**
 * Class Comments
 *
 * @property string $handler_hash
 * @property string $handler_class
 * @property int $object_id
 * @property int $user_id
 * @property int $id
 * @property int $tree
 * @property string $owner_title
 * @property string $user_name
 * @property string $user_email
 * @property string $ip_create
 * @property string $text
 * @property string $user_agent
 * @property int $created_at
 * @property int $updated_at
 * @property boolean $switch
 *
 * @package panix\mod\comments\models
 */
class Comments extends ActiveRecord
{

    const MODULE_ID = 'comments';
    const STATUS_WAITING = 0;
    const STATUS_APPROVED = 1;
    const STATUS_SPAM = 2;

    public $defaultStatus;
    //public $user_name;
    //public $user_email;


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
            'format' => 'userAgent',
            'filter' => false,
            'contentOptions' => ['class' => 'text-center'],
        ];

        $columns['ip_create'] = [
            'attribute' => 'ip_create',
            'format' => 'ip',
            'contentOptions' => ['class' => 'text-center'],
        ];

        $columns['user_email'] = [
            'attribute' => 'user_email',
            'format' => 'email',
            'contentOptions' => ['class' => 'text-left'],
        ];
        $columns['owner_title'] = [
            'attribute' => 'owner_title',
            'format' => 'html',
            'contentOptions' => ['class' => 'text-center'],
        ];
        $columns['handler_class'] = [
            'attribute' => 'handler_class',
            'format' => 'html',
            'contentOptions' => ['class' => 'text-left'],
        ];
        $columns['object_id'] = [
            'attribute' => 'object_id',
            'format' => 'html',
            'contentOptions' => ['class' => 'text-center'],
        ];

        $columns['created_at'] = [
            'attribute' => 'created_at',
            'class' => 'panix\engine\grid\columns\jui\DatepickerColumn',
        ];
        $columns['updated_at'] = [
            'attribute' => 'updated_at',
            'class' => 'panix\engine\grid\columns\jui\DatepickerColumn',
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
            // $this->user_name = Yii::$app->user->getDisplayName();
            // $this->user_email = Yii::$app->user->email;
        }
    }

    public function beforeSave($insert)
    {

        if (parent::beforeSave($insert)) {

            if (!Yii::$app->user->isGuest) {
                $this->user_name = Yii::$app->user->getDisplayName();
                $this->user_email = Yii::$app->user->email;
                $this->handler_hash = CMS::hash($this->handler_class);
            }
            //$this->text = htmlspecialchars($this->text);
            return true;
        } else {
            return false;
        }
    }


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
        if ($this->user) {
            return $this->user->getAvatarUrl($size);
        } else {
            return Yii::$app->user->getGuestAvatarUrl($size);
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

    public function getHandler()
    {
        return $this->hasOne($this->handlerClass, ['id' => 'object_id']);
    }

    public function behaviors()
    {
        return ArrayHelper::merge([
            'tree' => [
                'class' => NestedSetsBehavior::class,
                'hasManyRoots' => true
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
            $rulesGuest[] = ['user_email', 'email'];
        } else {
            $rulesGuest = [];
        }
        $rules[] = [['text', 'user_name', 'user_email', 'handler_class'], 'filter', 'filter' => '\yii\helpers\HtmlPurifier::process']; // XSS security
        $rules[] = [['user_agent', 'ip_create', 'user_name', 'user_email', 'text', 'owner_title', 'handler_class'], 'string'];

        $rules[] = [['text', 'object_id', 'owner_title', 'handler_class'], 'required'];
        //  $rules[] = ['date_create', 'date', 'format' => 'yyyy-M-d H:m:s'];
        return \yii\helpers\ArrayHelper::merge($rules, $rulesGuest);
    }

    /**
     * @return bool
     */
    public function hasAccessControl()
    {
        $conf = (int)Yii::$app->settings->get('comments', 'control_timeout');
        if ((time() - $conf <= $this->created_at) && $this->user_id == Yii::$app->user->id) {
            return true;
        }
        return false;
    }

    /**
     * @param Comments $comment
     */
    public function sendNotifyReply(Comments $comment)
    {
        /*$mailer = Yii::$app->mailer;
        $mailer->htmlLayout = "layouts/html";
        $mailer->compose(['html' => '@comments/mail/notify'], ['model' => $this, 'comment' => $comment])
            ->setFrom(['noreply@' . Yii::$app->request->serverName => Yii::$app->name . ' robot'])
            ->setTo([Yii::$app->settings->get('app', 'email') => Yii::$app->name])
            ->setSubject(Yii::t('comments/default', 'На Ваш комментарий ответели'))
            ->send();*/
    }

}
