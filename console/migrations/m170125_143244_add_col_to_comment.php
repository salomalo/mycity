<?php

use yii\db\Migration;

/**
 * Handles adding col to table `comment`.
 */
class m170125_143244_add_col_to_comment extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn('comment', 'rating_business', $this->integer());
        $this->addColumn('comment', 'business_type', $this->integer());
        $this->addColumn('comment', 'correct_price', $this->integer());
        $this->addColumn('comment', 'product_availability', $this->integer());
        $this->addColumn('comment', 'correct_description', $this->integer());
        $this->addColumn('comment', 'order_executed_on_time', $this->integer());
        $this->addColumn('comment', 'rating_callback', $this->integer());
        $this->addColumn('comment', 'rating_recommend', $this->integer());
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropColumn('comment', 'rating_business');
        $this->dropColumn('comment', 'business_type');
        $this->dropColumn('comment', 'correct_price');
        $this->dropColumn('comment', 'product_availability');
        $this->dropColumn('comment', 'correct_description');
        $this->dropColumn('comment', 'order_executed_on_time');
        $this->dropColumn('comment', 'rating_callback');
        $this->dropColumn('comment', 'rating_recommend');
    }
}
