<?php

namespace panix\mod\comments\migrations;

/**
 * Generation migrate by PIXELION CMS
 * @author PIXELION CMS development team <dev@pixelion.com.ua>
 *
 * Class m180917_195313_comments
 */
use panix\engine\db\Migration;
use panix\mod\comments\models\Comments;

class m180917_195313_comments extends Migration
{
    public $settingsForm = 'panix\mod\comments\models\SettingsForm';

    /**
     * {@inheritdoc}
     */
    public function up()
    {
        $this->createTable(Comments::tableName(), [
            'id' => $this->primaryKey()->unsigned(),
            'user_id' => $this->integer()->null()->unsigned(),
            'handler_class' => $this->string(255),
            'handler_hash' => $this->string(8)->null(),
            'object_id' => $this->integer()->null()->unsigned(),
            'tree' => $this->smallInteger()->null()->unsigned(),
            'lft' => $this->smallInteger()->null()->unsigned(),
            'rgt' => $this->smallInteger()->null()->unsigned(),
            'depth' => $this->smallInteger()->null()->unsigned(),
            'text' => $this->text()->null(),
            'ip_create' => $this->string(100),
            'owner_title' => $this->string(255),
            'user_name' => $this->string(255),
            'user_email' => $this->string(255),
            'user_agent' => $this->string(255),
            'switch' => $this->boolean()->defaultValue(1),
            'created_at' => $this->integer(11)->null(),
            'updated_at' => $this->integer(11)->null(),
        ], $this->tableOptions);


        $this->createIndex('lft', Comments::tableName(), 'lft');
        $this->createIndex('user_id', Comments::tableName(), 'user_id');
        $this->createIndex('tree', Comments::tableName(), 'tree');
        $this->createIndex('rgt', Comments::tableName(), 'rgt');
        $this->createIndex('depth', Comments::tableName(), 'depth');
        $this->createIndex('object_id', Comments::tableName(), 'object_id');
        $this->createIndex('handler_hash', Comments::tableName(), 'handler_hash');

        $this->loadSettings();
    }

    /**
     * {@inheritdoc}
     */
    public function down()
    {
        $this->dropTable(Comments::tableName());
    }

}
