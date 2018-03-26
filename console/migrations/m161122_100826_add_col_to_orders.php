<?php

use yii\db\Migration;

class m161122_100826_add_col_to_orders extends Migration
{
    public function up()
    {
        $this->addColumn('orders', 'idSeller', $this->integer());
    }

    public function down()
    {
        $this->dropColumn('orders', 'idSeller');
    }
}
