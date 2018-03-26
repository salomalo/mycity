<?php

use yii\db\Migration;

class m161101_152357_add_columns_to_profile extends Migration
{
    public function up()
    {
        $table = 'profile';

        $this->addColumn($table, 'address', $this->string());
        $this->addColumn($table, 'phone', $this->string());
        $this->addColumn($table, 'fio', $this->string());
    }

    public function down()
    {
        $table = 'profile';

        $this->dropColumn($table, 'address');
        $this->dropColumn($table, 'phone');
        $this->dropColumn($table, 'fio');
    }

    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }

    public function safeDown()
    {
    }
    */
}
