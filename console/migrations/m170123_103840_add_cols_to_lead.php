<?php

use yii\db\Migration;

/**
 * Handles adding cols to table `lead`.
 */
class m170123_103840_add_cols_to_lead extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn('lead', 'utm_source', $this->text());
        $this->addColumn('lead', 'utm_campaign', $this->text());
        $this->addColumn('lead', 'status', $this->integer());
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropColumn('lead', 'utm_source');
        $this->dropColumn('lead', 'utm_campaign');
        $this->dropColumn('lead', 'status');
    }
}
