<?php

use yii\db\Migration;

/**
 * Handles the creation for table `table_subscription`.
 */
class m161125_103329_create_table_subscription extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('subscription', [
            'id' => $this->primaryKey(),
            'email' => $this->string()->notNull(),
            'name' => $this->string()->notNull(),
            'created_at' => $this->dateTime(),
            'status' => $this->integer()->notNull(),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('subscription');
    }
}
