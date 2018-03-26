<?php

class m140811_095432_actions extends yii\db\Migration
{
    public function up()
    {
        $this->createTable('action_category', [
            'id' => $this->primaryKey(),
            'title' => $this->text()->notNull(),
            'image' => $this->string(),
            'seo_description' => $this->text(),
            'seo_keywords' => $this->text(),
            'seo_title' => $this->text(),
            'dateUpdate' => $this->date()->defaultExpression('NOW()'),
            'sitemap_en' => "en_type DEFAULT '0'",
            'sitemap_priority' => $this->string(3),
            'sitemap_changefreq' => $this->string(),
        ]);

        $this->createTable('action', [
            'id' => $this->primaryKey(),
            'idCompany' => $this->integer()->notNull(),
            'idCategory' => $this->integer()->notNull(),
            'title' => $this->text()->notNull(),
            'description' => $this->text()->notNull(),
            'image' => $this->string(),
            'price' => $this->float(),
            'dateStart' => $this->timestamp(),
            'dateEnd' => $this->timestamp(),
            'url' => $this->string(),
            'seo_description' => $this->text(),
            'seo_keywords' => $this->text(),
            'seo_title' => $this->text(),
            'dateUpdate' => $this->date()->defaultExpression('NOW()'),
            'sitemap_en' => "en_type DEFAULT '0'",
            'sitemap_priority' => $this->string(3),
            'sitemap_changefreq' => $this->string(),
        ]);
    }

    public function down()
    {
        $this->dropTable('action_category');
        $this->dropTable('action');
    }
}
