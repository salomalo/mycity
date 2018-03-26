<?php

class m160708_065641_create_business_custom_field extends yii\db\Migration
{
    private $cf = 'business_custom_field';
    private $cf_dv = 'business_custom_field_default_val';
    private $cf_v = 'business_custom_field_value';
    private $cf_l = 'business_category_custom_field_link';

    public function up()
    {
        //--------------------------------------------------------------------------------------------------------------
        //Table of CF
        $this->createTable($this->cf, [
            'id' => $this->primaryKey(),
            'title' => $this->string(500)->notNull(),
            'multiple' => $this->integer(1)->notNull(),
            'filter_type' => $this->integer(),
        ]);


        //--------------------------------------------------------------------------------------------------------------
        //Table of CF default values
        $this->createTable($this->cf_dv, [
            'id' => $this->primaryKey(),
            'custom_field_id' => $this->integer()->notNull(),
            'value' => $this->string()->notNull(),
            'value_numb' => $this->float(),
        ]);

        $this->createIndex("{$this->cf_dv}_index_custom_field_id", $this->cf_dv, 'custom_field_id');
        $this->createIndex("{$this->cf_dv}_index_cf_def_val", 'business_custom_field_default_val', 'value_numb');

        $this->addForeignKey("{$this->cf_dv}_custom_field_id_foreign", $this->cf_dv, 'custom_field_id', 'business_custom_field', 'id', 'CASCADE', 'CASCADE');


        //--------------------------------------------------------------------------------------------------------------
        //Table of CF values
        $this->createTable($this->cf_v, [
            'id' => $this->primaryKey(),
            'business_id' => $this->integer()->notNull(),
            'custom_field_id' => $this->integer()->notNull(),
            'value_id' => $this->integer(),
            'value' => $this->string(),
            'value_numb' => $this->float(),
        ]);

        $this->createIndex("{$this->cf_v}_index_business_id_custom_field_id", $this->cf_v, ['business_id', 'custom_field_id']);
        $this->createIndex("{$this->cf_v}_index_custom_field_id", $this->cf_v, 'custom_field_id');
        $this->createIndex("{$this->cf_v}_index_value_id", $this->cf_v, 'value_id');
        $this->createIndex("{$this->cf_v}_index_cf_val", $this->cf_v, 'value_numb');

        $this->addForeignKey("{$this->cf_v}_business_id_foreign", $this->cf_v, 'business_id', 'business', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey("{$this->cf_v}_custom_field_id_foreign", $this->cf_v, 'custom_field_id', 'business_custom_field', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey("{$this->cf_v}_value_id_foreign", $this->cf_v, 'value_id', 'business_custom_field_default_val', 'id', 'RESTRICT', 'CASCADE');


        //--------------------------------------------------------------------------------------------------------------
        //Table of links between categories and CF
        $this->createTable($this->cf_l, [
            'id' => $this->primaryKey(),
            'business_category_id' => $this->integer()->notNull(),
            'custom_field_id' => $this->integer()->notNull(),
        ]);

        $this->createIndex("{$this->cf_l}_index_business_category_id_custom_field_id", $this->cf_l, ['business_category_id', 'custom_field_id']);
        $this->createIndex("{$this->cf_l}_index_custom_field_id", $this->cf_l, 'custom_field_id');

        $this->addForeignKey("{$this->cf_l}_business_category_id_foreign", $this->cf_l, 'business_category_id', 'business_category', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey("{$this->cf_l}_custom_field_id_foreign", $this->cf_l, 'custom_field_id', 'business_custom_field', 'id', 'CASCADE', 'CASCADE');
    }

    public function down()
    {
        $this->dropTable($this->cf_l);
        $this->dropTable($this->cf_v);
        $this->dropTable($this->cf_dv);
        $this->dropTable($this->cf);
    }
}
