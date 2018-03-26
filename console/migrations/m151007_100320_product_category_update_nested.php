<?php

class m151007_100320_product_category_update_nested extends yii\db\Migration
{
    public function up()
    {
        $this->db->createCommand('UPDATE product_category SET depth=depth-1 WHERE depth>0')->execute();
    }

    public function down()
    {
        echo "m151007_100320_product_category_update_nested cannot be reverted.\n";

        return false;
    }
}
