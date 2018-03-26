<?php

class m140702_055511_job extends \yii\db\Migration
{
    public function up()
    {
        $this->createTable('work_vacantion', [
            'id' => $this->primaryKey(),
            'idCompany' => $this->integer(),
            'idCategory' => $this->integer()->notNull(),
            'idCity' => $this->integer()->notNull(),
            'idUser' => $this->integer()->notNull(),
            'title' => $this->string()->notNull(),
            'description' => $this->text()->notNull(),
            'proposition' => $this->text()->notNull(),
            'phone' => $this->string(),
            'email' => $this->string(),
            'skype' => $this->string(),
            'name' => $this->string()->notNull(),
            'salary' => $this->string(),
            'isFullDay' => $this->integer()->defaultValue(0),
            'isOffice' => $this->integer()->defaultValue(0),
            'experience' => $this->string()->notNull(),
            'male' => $this->integer()->defaultValue(1),
            'minYears' => $this->string(),
            'maxYears' => $this->string(),
            'dateCreate' => $this->timestamp(),
            'education' => $this->integer(),
            'url' => $this->string(),
            'seo_description' => $this->text(),
            'seo_keywords' => $this->string(),
            'seo_title' => $this->string(),
            'dateUpdate' => $this->date()->defaultExpression('NOW()'),
            'sitemap_en' => "en_type DEFAULT '1'",
            'sitemap_priority' => $this->string(3),
            'sitemap_changefreq' => $this->string(),
        ]);
        $this->createIndex('work_vacantion_indexes', 'work_vacantion', ['idCompany', 'idCategory', 'idCity', 'url "text_pattern_ops"']);

        $this->createTable('work_resume', [
            'id' => $this->primaryKey(),
            'idCategory' => $this->integer()->notNull(),
            'idUser' => $this->integer()->notNull(),
            'idCity' => $this->integer()->notNull(),
            'title' => $this->string()->notNull(),
            'description' => $this->text()->notNull(),
            'name' => $this->string()->notNull(),
            'year' => $this->string(),
            'experience' => $this->string()->notNull(),
            'male' => $this->integer()->defaultValue(1),
            'salary' => $this->string(),
            'isFullDay' => $this->integer()->defaultValue(0),
            'isOffice' => $this->integer()->defaultValue(0),
            'phone' => $this->string(),
            'email' => $this->string(),
            'skype' => $this->string(),
            'photoUrl' => $this->string(),
            'dateCreate' => $this->timestamp(),
            'education' => $this->integer(),
            'url' => $this->string(),
            'seo_description' => $this->text(),
            'seo_keywords' => $this->string(),
            'seo_title' => $this->string(),
            'dateUpdate' => $this->date()->defaultExpression('NOW()'),
            'sitemap_en' => "en_type DEFAULT '1'",
            'sitemap_priority' => $this->string(3),
            'sitemap_changefreq' => $this->string(),
        ]);
        $this->createIndex('work_resume_indexes', 'work_resume', ['idCategory', 'idCity', 'url "text_pattern_ops"']);

        $this->createTable('work_category', [
            'id' => $this->primaryKey(),
            'title' => $this->text()->notNull(),
            'seo_description' => $this->text(),
            'seo_keywords' => $this->text(),
            'seo_title' => $this->text(),
            'dateUpdate' => $this->date()->defaultExpression('NOW()'),
            'sitemap_en' => "en_type DEFAULT '1'",
            'sitemap_priority' => $this->string(3),
            'sitemap_changefreq' => $this->string(),
        ]);
        $this->createIndex('work_category_indexes', 'work_category', ['url "text_pattern_ops"']);
    }

    public function down()
    {
        $this->dropTable('work_vacantion');
        $this->dropTable('work_resume');
        $this->dropTable('work_category');
    }
}
