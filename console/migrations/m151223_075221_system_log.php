<?php

class m151223_075221_system_log extends yii\db\Migration
{
    public function up()
    {
        $this->createTable('system_log', [
            'id' => $this->primaryKey(),
            'dateCreate' => $this->dateTime()->notNull(),
            'description' => $this->text(),
        ]);
    }

    public function down()
    {
        $this->dropTable('system_log');
    }
}
