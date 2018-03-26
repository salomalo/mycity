<?php

class m150722_093650_orders_ads extends yii\db\Migration
{
    public function up()
    {
        $this->createTable('orders_ads', [
            'id' => $this->primaryKey(),
            'pid' => $this->integer(),
            'idAds' => $this->string(),
            'countAds' => $this->integer(),
            'idBusiness' => $this->integer(),
            'status' => $this->integer(),
        ]);
    }

    public function down()
    {
        $this->dropTable('orders_ads');
    }
}
