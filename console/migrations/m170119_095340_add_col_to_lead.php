<?php

use yii\db\Migration;

/**
 * Handles adding col to table `lead`.
 */
class m170119_095340_add_col_to_lead extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn('lead', 'date_create', $this->dateTime()->defaultExpression('NOW()'));
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropColumn('lead', 'date_create');
    }
}
