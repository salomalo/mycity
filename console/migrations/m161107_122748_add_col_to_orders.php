<?php

use yii\db\Migration;

class m161107_122748_add_col_to_orders extends Migration
{
    public function up()
    {
        $table = 'orders';

        $this->addColumn($table, 'delivery', $this->string());
        $this->addColumn($table, 'office', $this->string());
    }

    public function down()
    {
        $table = 'orders';

        $this->dropColumn($table, 'delivery');
        $this->dropColumn($table, 'office');
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
