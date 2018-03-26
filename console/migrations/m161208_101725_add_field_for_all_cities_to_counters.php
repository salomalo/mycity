<?php

use yii\db\Migration;

/**
 * Handles adding field_for_all_cities to table `counters`.
 */
class m161208_101725_add_field_for_all_cities_to_counters extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn('counter', 'for_all_cities', $this->integer()->defaultValue(0)->notNull());
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
    }
}
