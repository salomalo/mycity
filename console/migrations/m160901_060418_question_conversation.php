<?php

use yii\db\Migration;

class m160901_060418_question_conversation extends Migration
{
    public function up()
    {
        $table = 'question_conversation';

        $this->createTable($table, [
            'id' => $this->primaryKey(),
            'status' => $this->integer()->notNull(),
            'user_id' => $this->integer(),
            'title' => $this->string()->notNull(),
            'object_type' => $this->integer()->notNull(),
            'object_id' => $this->string()->notNull(),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP')->notNull(),
        ]);

        $this->createIndex("{$table}_object_id_index", $table, ['object_id', 'object_type']);

        $this->createIndex("{$table}_user_id_index", $table, 'user_id');

        $this->addForeignKey("{$table}_user_id_foreign_key", $table, 'user_id', 'user', 'id', 'CASCADE', 'CASCADE');

        //--------------------------------------------------------------------------------------------------------------

        $table = 'question';

        $this->createTable($table, [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'conversation_id' => $this->integer()->notNull(),
            'text' => $this->text()->notNull(),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP')->notNull(),
        ]);

        $this->createIndex("{$table}_user_id_index", $table, 'user_id');
        $this->createIndex("{$table}_conversation_id_index", $table, 'conversation_id');

        $this->addForeignKey("{$table}_user_id_foreign_key", $table, 'user_id', 'user', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey("{$table}_conversation_id_foreign_key", $table, 'conversation_id', 'question_conversation', 'id', 'CASCADE', 'CASCADE');
    }

    public function down()
    {
        echo "m160901_060418_question_conversation cannot be reverted.\n";

        return false;
    }
}

