<?php

use yii\db\Migration;

/**
 * Handles adding col to table `product_category`.
 */
class m170123_162047_add_col_to_product_category extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn('product_category', 'description', $this->text());
        $this->addColumn('product_category', 'hide_description', $this->text());
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropColumn('product_category', 'description');
        $this->dropColumn('product_category', 'hide_description');
    }
}
