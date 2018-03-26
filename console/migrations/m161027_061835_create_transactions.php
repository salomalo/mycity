<?php

use yii\db\Migration;

/**
 * Handles the creation for table `transactions`.
 */
class m161027_061835_create_transactions extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('transactions', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'object_type' => $this->integer()->notNull(),
            'object_id' => $this->integer()->notNull(),
            'amount' => $this->integer(),
            'paid_from' => $this->timestamp(),
            'paid_to' => $this->timestamp(),
            'created_at' => $this->dateTime(),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('transactions');
    }
}
