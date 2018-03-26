<?php

class m140629_053823_files extends \yii\db\Migration
{
    public function up()
    {
        $this->createTable('file', [
            'id' => $this->primaryKey(),
            'type' => $this->string()->notNull(),
            'pid' => $this->integer(),
            'size' => $this->integer(),
            'name' => $this->string()->notNull(),
            'dateCreate' => $this->timestamp(),
            'pidMongo' => $this->string(),
        ]);
        $this->createIndex('indexFile', 'file', ['type', 'pid']);
    }

    public function down()
    {
        $this->dropTable('file');
    }
}
