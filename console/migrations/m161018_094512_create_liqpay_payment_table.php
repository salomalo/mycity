<?php
use yii\db\Migration;

/**
 * Handles the creation for table `liqpay_payment_table`.
 */
class m161018_094512_create_liqpay_payment_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('liqpay_payment', [
            'id' => $this->primaryKey(),
            'status' => $this->string(),
            'order_id' => $this->string()->unique(),
            'action' => $this->string(),
            'amount' => $this->string(),
            'currency' => $this->string(),
            'data' => $this->text(),
            'created_at' => $this->dateTime(),
            'updated_at' => $this->dateTime(),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('liqpay_payment_table');
    }
}
