<?php

class m150722_093633_orders extends yii\db\Migration
{
    public function up()
    {
        $this->createTable('orders',[
            'id'=> $this->primaryKey(),
            'idUser' => $this->integer(),
            'idCity' => $this->integer(),
            'address' => $this->string(),
            'phone' => $this->string(),
            'fio' => $this->string(),
            'description' => $this->text(),
            'paymentType' => $this->integer(),
            'dateCreate' => $this->integer(),
            ]); 
    }

    public function down()
    {
        echo "m150722_093633_orders cannot be reverted.\n";

        return false;
    }
}
