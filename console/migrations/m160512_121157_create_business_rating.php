<?php

class m160512_121157_create_business_rating extends yii\db\Migration
{
    public function up()
    {
        $this->createTable('business_rating', [
            'id' => $this->primaryKey(),
            'business_id' => $this->integer(),
            'user_id' => $this->integer(),
            'date' => $this->timestamp(),
            'rating' => $this->integer(),
        ]);
        $this->createIndex('index_by_user_and_business', 'business_rating', ['user_id', 'business_id']);
    }

    public function down()
    {
        $this->dropTable('business_rating');
    }
}
