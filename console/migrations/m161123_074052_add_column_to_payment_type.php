<?php

use yii\db\Migration;

/**
 * Handles adding column to table `payment_type`.
 */
class m161123_074052_add_column_to_payment_type extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn('payment_type', 'image', $this->string());

        $table_upt = 'user_payment_type';
        $this->createTable($table_upt, [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'payment_type_id' => $this->integer()->notNull(),
            'description' => $this->text(),
            'created_at' => $this->dateTime(),
        ]);

        $this->createIndex("{$table_upt}_user_id", $table_upt, 'user_id');
        $this->createIndex("{$table_upt}_payment_type_id", $table_upt, 'payment_type_id');
        $this->addForeignKey("{$table_upt}_user_id_fk", $table_upt, 'user_id', 'user', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey("{$table_upt}_payment_type_id_fk", $table_upt, 'payment_type_id', 'payment_type', 'id', 'CASCADE', 'CASCADE');

        $table_uptb = 'user_payment_type_business';
        $this->createTable($table_uptb, [
            'id' => $this->primaryKey(),
            'user_payment_type_id' => $this->integer()->notNull(),
            'business_id' => $this->integer()->notNull(),
        ]);

        $this->createIndex("{$table_uptb}_user_payment_type_id", $table_uptb, 'user_payment_type_id');
        $this->createIndex("{$table_uptb}_business_id", $table_uptb, 'business_id');
        $this->addForeignKey("{$table_uptb}_user_payment_type_id_fk", $table_uptb, 'user_payment_type_id', $table_upt, 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey("{$table_uptb}_business_id_fk", $table_uptb, 'business_id', 'business', 'id', 'CASCADE', 'CASCADE');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
    }
}
