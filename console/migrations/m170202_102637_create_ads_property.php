<?php

use yii\db\Migration;

/**
 * Handles the creation for table `ads_property`.
 */
class m170202_102637_create_ads_property extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $table_upt = 'ads_property';
        $this->createTable($table_upt, [
            'id' => $this->primaryKey(),
            'category_id' => $this->integer()->notNull(),
            'provider_id' => $this->integer(),
            'company_id' => $this->integer(),
            'business_id' => $this->integer(),
            'user_id' => $this->integer()->notNull(),
        ]);

        $this->createIndex("{$table_upt}_category_id", $table_upt, 'category_id');
        $this->addForeignKey("{$table_upt}_category_id_fk", $table_upt, 'category_id', 'product_category', 'id', 'CASCADE', 'CASCADE');
        $this->createIndex("{$table_upt}_provider_id", $table_upt, 'provider_id');
        $this->addForeignKey("{$table_upt}_provider_id_fk", $table_upt, 'provider_id', 'provider', 'id', 'CASCADE', 'CASCADE');
        $this->createIndex("{$table_upt}_company_id", $table_upt, 'company_id');
        $this->addForeignKey("{$table_upt}_company_id_fk", $table_upt, 'company_id', 'product_company', 'id', 'CASCADE', 'CASCADE');
        $this->createIndex("{$table_upt}_business_id", $table_upt, 'business_id');
        $this->addForeignKey("{$table_upt}_business_id_fk", $table_upt, 'business_id', 'business', 'id', 'CASCADE', 'CASCADE');
        $this->createIndex("{$table_upt}_user_id", $table_upt, 'user_id');
        $this->addForeignKey("{$table_upt}_user_id_fk", $table_upt, 'user_id', 'user', 'id', 'CASCADE', 'CASCADE');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('ads_property');
    }
}
