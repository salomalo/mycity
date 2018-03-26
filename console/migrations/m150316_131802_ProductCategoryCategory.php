<?php

class m150316_131802_ProductCategoryCategory extends yii\db\Migration
{
    public function up()
    {
        $this->createTable('ProductCategoryCategory', [
            'id' => $this->primaryKey(),
            'ProductCategory' => $this->integer(),
            'ProductCompany' => $this->integer(),
        ]);
    }

    public function down()
    {
        $this->dropTable('ProductCategoryCategory');
    }
}
