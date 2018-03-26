<?php

class m160520_115300_create_parse_history extends yii\db\Migration
{
    public function up()
    {
        $this->createTable('parse_history', [
            'id' => $this->primaryKey(),
            'element' => $this->string(),
            'parser_id' => $this->integer(),
            'date' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
        ]);
    }

    public function down()
    {
        $this->dropTable('parse_history');
    }
}
