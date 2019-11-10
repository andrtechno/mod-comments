<?php

namespace panix\mod\comments\components;

use panix\engine\CMS;
use panix\mod\comments\models\Comments;
use panix\mod\shop\models\Product;
use yii\caching\DbDependency;
use yii\db\ActiveRecord;
use yii\base\Behavior;


/**
 * Class CommentBehavior
 *
 * @property string $handlerHash
 * @property string $handlerClass
 *
 * @package panix\mod\comments\components
 */
class CommentBehavior extends Behavior
{

    /**
     * @var string model primary key attribute
     */
    public $pk = 'id';
    public $class_name;
    public $cacheDuration = 3600 * 12;
    /**
     * @var string alias to class.
     */
    public $handlerClass;

    /**
     * @var string attribute name to present comment owner in admin panel. e.g: name - references to Page->name
     */
    public $owner_title;

    public function events()
    {
        return [
            ActiveRecord::EVENT_AFTER_DELETE => 'afterDelete',
        ];
    }

    /**
     * @return string pk name
     */
    public function getObjectPkAttribute()
    {
        return $this->pk;
    }

    public function getHandlerClass()
    {
        $class = ($this->handlerClass) ? $this->owner : get_class($this->owner);
        return $class;
    }

    public function getHandlerHash()
    {
        return CMS::hash($this->getHandlerClass());
    }

    public function getOwnerTitle()
    {
        $attr = $this->owner_title;
        return $this->owner->$attr;
    }

    public function attach($owner)
    {
        parent::attach($owner);
    }

    /**
     * Deleted all comments
     */
    public function afterDelete()
    {
        $pk = $this->getObjectPkAttribute();
        Comments::deleteAll([
            'handler_hash' => $this->handlerHash,
            'object_id' => $this->owner->{$pk}
        ]);
        //  return parent::afterDelete($event);
    }

    /**
     * @return string approved comments count for object
     */
    public function getCommentsCount()
    {
        $pk = $this->getObjectPkAttribute();
        return Comments::find()
            ->published()
            ->where([
                'handler_hash' => $this->getHandlerHash(),
                'object_id' => $this->owner->{$pk}
            ])
            ->cache($this->cacheDuration, new DbDependency([
                'sql' => 'SELECT COUNT(*) FROM ' . Comments::tableName() . ' WHERE `handler_hash`="' . $this->handlerHash . '" AND `object_id`="' . $this->owner->{$pk} . '"'
            ]))
            ->count();
    }

}
