<?php

class m151013_102033_fix_model_lang extends yii\db\Migration
{
    public function up()
    {
        $this->alterColumn('business_category', 'seo_keywords', $this->text());
        $this->alterColumn('business_category', 'seo_title', $this->text());
    }

    public function down()
    {
        echo "m151013_102033_fix_model_lang cannot be reverted.\n";

        return false;
    }
}
