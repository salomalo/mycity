<?php

use yii\db\Migration;

class m161206_084646_fix_business_performance extends Migration
{
    public function up()
    {
        $this->createIndex('business_category_sitemap_index', 'business_category', ['sitemap_en', 'id']);
        $this->createIndex('business_ratio_id_index', 'business', ['ratio', 'id']);
    }

    public function down()
    {
        echo "m161206_084646_fix_business_performance cannot be reverted.\n";

        return false;
    }
}
