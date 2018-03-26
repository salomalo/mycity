<?php

use yii\db\Migration;

class m160411_120917_create_column_in_wall extends Migration
{
    public function up()
    {
        $this->addColumn('wall', 'published', 'int');
        $this->createIndex('index_wall', 'wall', ['idCity', 'published']);
    }

    public function down()
    {
    }
}
