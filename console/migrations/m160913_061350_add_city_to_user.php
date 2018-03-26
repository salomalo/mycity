<?php

use yii\db\Migration;

class m160913_061350_add_city_to_user extends Migration
{
    public function up()
    {
        $this->addColumn('user', 'city_id', $this->integer());
        $this->createIndex('user_city_index', 'user', 'city_id');
        $this->addForeignKey('user_city_fk', 'user', 'city_id', 'city', 'id', 'CASCADE', 'CASCADE');
    }

    public function down()
    {
        $this->dropForeignKey('user_city_fk', 'user');
        $this->dropIndex('user_city_index', 'user');
        $this->dropColumn('user', 'city_id');
    }
}
