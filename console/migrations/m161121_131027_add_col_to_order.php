<?php

use yii\db\Migration;

class m161121_131027_add_col_to_order extends Migration
{
    public function up()
    {
        $this->addColumn('orders', 'status', $this->integer()->defaultValue(1));
    }

    public function down()
    {
         $this->dropColumn('orders', 'status');
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
