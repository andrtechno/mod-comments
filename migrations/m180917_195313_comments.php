<?php

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
            'user_id' => $this->integer()->unsigned(),
            'manufacturer_id' => $this->integer()->unsigned(),
            'category_id' => $this->integer()->unsigned(),
            'main_category_id' => $this->integer()->unsigned(),

            'ordern' => $this->integer(),
        ], $this->tableOptions);


        $this->createIndex('created_at', Comments::tableName(), 'created_at');
        $this->createIndex('views', Comments::tableName(), 'views', 0);
        $this->createIndex('ordern', Comments::tableName(), 'ordern', 0);
        $this->createIndex('main_category_id', Comments::tableName(), 'main_category_id');


    }

    /**
     * {@inheritdoc}
     */
    public function down()
    {
        $this->dropTable(Comments::tableName());
    }

}
