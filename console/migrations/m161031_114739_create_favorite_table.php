<?php

use yii\db\Migration;

/**
 * Handles the creation for table `favorite_table`.
 */
class m161031_114739_create_favorite_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('favorite', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'object_type' => $this->integer()->notNull(),
            'object_id' => $this->integer()->notNull(),
            'created_at' => $this->dateTime(),
        ]);

        $this->createIndex('favorite_index_user_id', 'favorite', 'user_id');

        $this->addForeignKey('favorite_business_id_foreign', 'favorite', 'user_id', 'user', 'id', 'CASCADE', 'CASCADE');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('favorite_table');
    }
}
