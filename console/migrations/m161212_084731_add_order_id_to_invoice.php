<?php

use yii\db\Migration;

/**
 * Handles adding order_id to table `invoice`.
 */
class m161212_084731_add_order_id_to_invoice extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn('invoice', 'order_id', $this->string()->notNull()->defaultValue(''));
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
    }
}
