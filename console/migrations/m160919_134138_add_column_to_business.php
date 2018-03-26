<?php

use yii\db\Migration;

class m160919_134138_add_column_to_business extends Migration
{
    public function up()
    {
        $table = 'business';

        $this->addColumn($table, 'cinema_id', $this->integer());
    }

    public function down()
    {
        $table = 'business';

        $this->dropColumn($table, 'cinema_id');
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