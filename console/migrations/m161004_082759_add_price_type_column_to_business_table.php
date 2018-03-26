<?php

use yii\db\Migration;

/**
 * Handles adding price_type_column to table `business_table`.
 */
class m161004_082759_add_price_type_column_to_business_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn('business', 'price_type', $this->integer()->defaultValue(1)->notNull());
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropColumn('business_table', 'price_type');
    }
}
