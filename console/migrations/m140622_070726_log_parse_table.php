<?php

class m140622_070726_log_parse_table extends \yii\db\Migration
{
    public function up()
    {
        $this->createTable('logParse', [
            'id' => $this->primaryKey(),
            'idProduct' => $this->string(),
            'message' => $this->text(),
            'isFail' => $this->integer()->defaultValue(0),
            'dateCreate' => $this->timestamp(),
            'url' => $this->text(),
        ]);
    }

    public function down()
    {
        $this->dropTable('logParse');
    }
}
