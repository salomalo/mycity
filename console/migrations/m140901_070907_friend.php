<?php

class m140901_070907_friend extends yii\db\Migration
{
    public function up()
    {
        $this->createTable('friend', [
            'id' => $this->primaryKey(),
            'idUser' => $this->integer()->notNull(),
            'idFriend' => $this->integer()->notNull(),
            'status' => $this->integer()->notNull()->defaultValue(0),
        ]);
    }

    public function down()
    {
        $this->dropTable('friend');
    }
}
