<?php

class m160729_115426_ad_cols_to_profile extends yii\db\Migration
{
    public function up()
    {
        //Google analytics code
        $this->addColumn('city_detail', 'google_analytic', $this->string()->defaultValue(null));

        //Business parser info
        $this->addColumn('logParseBusiness', 'business_id', $this->integer());
        $this->addColumn('logParseBusiness', 'city_id', $this->integer());
        $this->addColumn('logParseBusiness', 'full_url', $this->string());

        //Profile additional fields
        $this->addColumn('profile', 'gender', $this->integer());
        $this->addColumn('profile', 'birth_city_id', $this->integer());
        $this->addColumn('profile', 'country_id', $this->integer());
        $this->addColumn('profile', 'birth_date', $this->date());
    }

    public function down()
    {
        echo "m160729_115426_ad_cols_to_profile cannot be reverted.\n";

        return false;
    }
}
