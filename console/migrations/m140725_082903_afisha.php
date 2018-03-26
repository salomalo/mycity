<?php

class m140725_082903_afisha extends yii\db\Migration
{
    public function up()
    {
        $this->createTable('afisha_category', [
            'id' => $this->primaryKey(),
            'pid' => $this->integer(),
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

        $this->createTable('afisha', [
            'id' => $this->primaryKey(),
            'idsCompany' => 'INT[] NOT NULL',
            'idCategory' => $this->integer()->notNull(),
            'title' => $this->text()->notNull(),
            'description' => $this->text(),
            'image' => $this->string(),
            'dateStart' => $this->timestamp(),
            'dateEnd' => $this->timestamp(),
            'rating' => $this->integer()->defaultValue(0),
            'times' => $this->string(),
            'price' => $this->string(),
            'isFilm' => $this->integer(1)->defaultValue(0),
            'genre' => $this->integer(),
            'year' => $this->integer(),
            'country' => $this->text(),
            'director' => $this->text(),
            'actors' => $this->text(),
            'budget' => $this->string(),
            'trailer' => $this->string(),
            'fullText' => $this->text(),
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
        $this->dropTable('afisha');
        $this->dropTable('afisha_category');
    }
}
