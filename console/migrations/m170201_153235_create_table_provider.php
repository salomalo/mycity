<?php

use yii\db\Migration;

/**
 * Handles the creation for table `table_provider`.
 */
class m170201_153235_create_table_provider extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $table_upt = 'provider';
        $this->createTable($table_upt, [
            'id' => $this->primaryKey(),
            'title' => $this->string(50)->notNull(),
            'description' => $this->string(255)->notNull(),
            'user_id' => $this->integer()->notNull(),
        ]);

        $this->createIndex("{$table_upt}_user_id", $table_upt, 'user_id');
        $this->addForeignKey("{$table_upt}_user_id_fk", $table_upt, 'user_id', 'user', 'id', 'CASCADE', 'CASCADE');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('provider');
    }
}
