<?php

namespace panix\mod\comments\components;

use panix\mod\comments\models\Comments;
use yii\db\ActiveRecord;
use yii\base\Behavior;

class CommentBehavior extends Behavior {

    /**
     * @var string model primary key attribute
     */
    public $pk = 'id';
    public $class_name;

    /**
     * @var string alias to class. e.g: application.store.models.ShopProduct or pages.models.Page
     */
    public $model;

    /**
     * @var string attribute name to present comment owner in admin panel. e.g: name - references to Page->name
     */
    public $owner_title;

    public function events() {
        return [
            ActiveRecord::EVENT_AFTER_DELETE => 'afterDelete',
        ];
    }

    /**
     * @return string pk name
     */
    public function getObjectPkAttribute() {
        return $this->pk;
    }

    public function getModelName() {
        return $this->model;
    }

    public function getOwnerTitle() {
        $attr = $this->owner_title;
        return $this->owner->$attr;
    }

    public function attach($owner) {
        parent::attach($owner);
    }

    /**
     * @return mixed
     */
    public function afterDelete() {


        $pk = $this->getObjectPkAttribute();
        Comments::deleteAll([
            'model' => $this->getModelName(),
            'object_id' => $this->getOwner()->$pk
        ]);

        //  return parent::afterDelete($event);
    }

    /**
     * @return string approved comments count for object
     */
    public function getCommentsCount() {

        $pk = $this->getObjectPkAttribute();
        return Comments::find()
                        ->published()
                        ->where(['model' => $this->getModelName(),'object_id' => $this->getOwner()->$pk])
                        ->count();
    }

}
