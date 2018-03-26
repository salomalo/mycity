<?php

use yii\db\Migration;

/**
 * Handles adding short_url_column to table `business`.
 */
class m161205_131920_add_short_url_column_to_business extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn('business', 'short_url', $this->string()->unique());
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
    }
}
