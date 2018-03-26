<?php

use yii\db\Migration;

class m160901_123209_add_col_to_conversation extends Migration
{
    public function up()
    {
        $table = 'question_conversation';

        $this->addColumn($table, 'owner_id', $this->integer());

        $this->createIndex("{$table}_owner_id_index", $table, 'owner_id');

        $this->addForeignKey("{$table}_owner_id_foreign_key", $table, 'owner_id', 'user', 'id', 'CASCADE', 'CASCADE');
    }

    public function down()
    {
        echo "m160901_123209_add_col_to_conversation cannot be reverted.\n";

        return false;
    }
}

