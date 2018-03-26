<?php

class m150730_101008_category_customfields extends yii\db\Migration
{
    public function up()
    {
        $this->createTable('customfield_category', [
            'id' => $this->primaryKey(),
            'title' => $this->text(),
            'order' => $this->integer()
        ]);

        $this->addColumn('product_customfield', 'idCategoryCustomfield', $this->integer());
        $this->addColumn('product_customfield', 'order', $this->integer());
    }

    public function down()
    {
        echo "m150730_101008_category_customfields cannot be reverted.\n";

        return false;
    }
}
