<?php

use yii\db\Migration;

/**
 * Handles adding col to table `user`.
 */
class m170119_102052_add_col_to_user extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn('user', 'phone', $this->string(255));
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropColumn('user', 'phone');
    }
}
