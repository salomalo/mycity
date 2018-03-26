<?php

class m151207_110323_rss_wall extends yii\db\Migration
{
    public function up()
    {
        $this->createTable('wall', [
            'id' => $this->primaryKey(),
            'pid' => $this->integer()->notNull(),
            'type' => $this->integer()->notNull(),
            'title' => $this->text(),
            'description' => $this->text(),
            'url' => $this->string(),
            'image' => $this->string(),
            'dateCreate' => $this->timestamp(),
            'idCity' => $this->integer(),
        ]);
    }

    public function down()
    {
        $this->dropTable('wall');
    }
}
