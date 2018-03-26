<?php

class m151214_093152_city_detail extends yii\db\Migration
{
    public function up()
    {
        $this->createTable('city_detail', [
            'id' => $this->integer()->notNull(),
            'title' => $this->text(),
            'about' => $this->text(),
            'seo_title' => $this->text(),
            'seo_description' => $this->text(),
            'seo_keywords' => $this->text(),
            'google_analytic' => $this->string(),
        ]);
    }

    public function down()
    {
        $this->dropTable('city_detail');
    }
}
