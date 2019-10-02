<?php

namespace panix\mod\comments\migrations;

/**
 * Generation migrate by PIXELION CMS
 * @author PIXELION CMS development team <dev@pixelion.com.ua>
 *
 * Class m180917_195313_comments
 */
use panix\mod\comments\models\Comments;
use panix\engine\db\Migration;

class m180917_195313_comments extends Migration
{

    /**
     * {@inheritdoc}
     */
    public function up()
    {
        $this->createTable(Comments::tableName(), [
            'id' => $this->primaryKey()->unsigned(),
            'user_id' => $this->integer()->null()->unsigned(),
            'tree' => $this->smallInteger()->notNull()->unsigned(),
            'lft' => $this->smallInteger()->notNull()->unsigned(),
            'rgt' => $this->smallInteger()->notNull()->unsigned(),
            'depth' => $this->smallInteger()->notNull()->unsigned(),
            'text' => $this->text()->notNull(),
            'ip_create' => $this->string(100),
            'owner_title' => $this->string(255),
            'user_name' => $this->string(255),
            'user_email' => $this->string(255),
            'user_agent' => $this->string(255),
            'handlerClass' => $this->string(255),
            'object_id' => $this->integer()->notNull()->unsigned(),
            'switch' => $this->boolean()->defaultValue(1),
            'created_at' => $this->integer(11)->null(),
            'updated_at' => $this->integer(11)->null(),
        ], $this->tableOptions);


        $this->createIndex('lft', Comments::tableName(), 'lft');
        $this->createIndex('user_id', Comments::tableName(), 'user_id');
        $this->createIndex('tree', Comments::tableName(), 'tree');
        $this->createIndex('rgt', Comments::tableName(), 'rgt');
        $this->createIndex('depth', Comments::tableName(), 'depth');
    }

    /**
     * {@inheritdoc}
     */
    public function down()
    {
        $this->dropTable(Comments::tableName());
    }

}
