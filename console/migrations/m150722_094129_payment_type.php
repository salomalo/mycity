<?php

class m150722_094129_payment_type extends yii\db\Migration
{
    public function up()
    {
        $this->createTable('payment_type', [
            'id' => $this->primaryKey(),
            'title' => $this->text(),
        ]);
    }

    public function down()
    {
        echo "m150722_094129_payment_type cannot be reverted.\n";

        return false;
    }
}
