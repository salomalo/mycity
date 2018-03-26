<?php

use yii\db\Migration;

class m161111_102232_remane_transactions_to_invoice extends Migration
{
    public function up()
    {
        $this->renameTable ('transactions', 'invoice');
        $this->addColumn('invoice', 'paid_status', $this->integer()->defaultValue(0));
        $this->addColumn('invoice', 'description', $this->string());
    }

    public function down()
    {
        $this->dropColumn('invoice', 'paid_status');
        $this->dropColumn('invoice', 'description');
        $this->renameTable('invoice', 'transactions');
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
