<?php

class m140729_072024_gallery extends yii\db\Migration
{
    public function up()
    {
        $this->createTable('gallery', [
            'id' => $this->primaryKey(),
            'type' => $this->integer()->notNull(),
            'pid' => $this->integer()->notNull(),
            'title' => $this->string()->notNull(),
        ]);
    }

    public function down()
    {
        $this->dropTable('gallery');
    }
}
