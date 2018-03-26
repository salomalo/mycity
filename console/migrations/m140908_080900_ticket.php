<?php

class m140908_080900_ticket extends yii\db\Migration
{
    public function up()
    {
        $this->createTable('ticket', [
            'id' => $this->primaryKey(),
            'idUser' => $this->integer()->notNull(),
            'title' => $this->string()->notNull(),
            'status' => $this->integer()->notNull(),
            'email' => $this->string()->notNull(),
            'dateCreate' => $this->timestamp(),
            'type' => $this->integer()->notNull()->defaultValue(1),
            'pid' => $this->integer()->notNull()->defaultValue(0),
        ]);

        $this->createTable('ticket_history', [
            'id' => $this->primaryKey(),
            'idTicket' => $this->integer()->notNull(),
            'body' => $this->text()->notNull(),
            'dateCreate' => $this->timestamp(),
            'idUser' => $this->integer()->notNull()->defaultValue(0),
        ]);
    }

    public function down()
    {
        $this->dropTable('ticket');
        $this->dropTable('ticket_history');
    }
}
