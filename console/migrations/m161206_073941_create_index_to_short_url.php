<?php

use yii\db\Migration;

/**
 * Handles the creation for table `index_to_short_url`.
 */
class m161206_073941_create_index_to_short_url extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createIndex('business_short_url_index', 'business', 'short_url', true);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('index_to_short_url');
    }
}
