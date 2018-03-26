<?php

class m161108_173900_add_col_to_order_ads extends yii\db\Migration
{
    public function up()
    {
        $table = 'orders_ads';

        $this->addColumn($table, 'idUser', $this->integer());
    }

    public function down()
    {
        $table = 'orders_ads';

        $this->dropColumn($table, 'idUser');
    }
}
