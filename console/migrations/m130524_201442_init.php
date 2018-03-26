<?php

class m130524_201442_init extends \yii\db\Migration
{
    public function up()
    {
        $this->execute("CREATE TYPE social_type AS ENUM ('post');");
        $this->execute("CREATE TYPE social_type_event AS ENUM ('like','unlike','rating');");
        $this->execute("CREATE TYPE en_type AS ENUM ('0','1');");
        $this->execute("CREATE TYPE ratio_type AS ENUM ('+1','-1');");

        $this->addPost();
        $this->addSocial();
        $this->addBusiness();
        $this->addProduct();

        $this->insert('post_category', ['title' => 'Реклама', 'image' => 'i001.png']);
        $this->insert('post_category', ['title' => 'События', 'image' => 'i002.png']);
        $this->insert('post_category', ['title' => 'Криминал', 'image' => 'i003.png']);
        $this->insert('post_category', ['title' => 'Политика', 'image' => 'i004.png']);
        $this->insert('post_category', ['title' => 'Бизнес', 'image' => 'i005.png']);
        $this->insert('post_category', ['title' => 'Власть', 'image' => 'i006.png']);
        $this->insert('post_category', ['title' => 'Аналитика', 'image' => 'i007.png']);
        $this->insert('post_category', ['title' => 'Культура', 'image' => 'i008.png']);
        $this->insert('post_category', ['title' => 'Общество', 'image' => 'i009.png']);
        $this->insert('post_category', ['title' => 'Проишествия', 'image' => 'i0010.png']);
        $this->insert('post_category', ['title' => 'Спорт', 'image' => 'i0011.png']);
        $this->insert('post_category', ['title' => 'Интервью', 'image' => 'icon_eco.png']);
    }

    private function addPost()
    {
        $this->createTable('post', [
            'id' => $this->primaryKey(),
            'idUser' => $this->integer()->notNull(),
            'idCategory' => $this->integer(),
            'title' => $this->text()->notNull(),
            'shortText' => $this->text()->notNull(),
            'fullText' => $this->text()->notNull(),
            'address' => $this->string(),
            'lat' => $this->double(),
            'lon' => $this->double(),
            'image' => $this->string(),
            'dateCreate' => $this->timestamp(),
            'status' => $this->integer()->notNull()->defaultValue(1),
            'url' => $this->string(),
            'business_id' => $this->integer(),
            'seo_description' => $this->text(),
            'seo_keywords' => $this->text(),
            'seo_title' => $this->text(),
            'dateUpdate' => $this->date()->defaultExpression('NOW()'),
            'sitemap_en' => "en_type DEFAULT '0'",
            'sitemap_priority' => $this->string(3),
            'sitemap_changefreq' => $this->string(),
        ]);

        $this->createTable('post_category', [
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
    }

    private function addSocial()
    {
        $this->createTable('social', [
            'id' => $this->primaryKey(),
            'pid' => $this->integer()->notNull(),
            'type' => 'social_type NOT NULL',
            'like' => $this->integer()->notNull(),
            'unlike' => $this->integer()->notNull(),
            'ratingSum' => $this->integer()->notNull(),
            'ratingCount' => $this->integer()->notNull(),
            'usersLikeIDs' => $this->text(),
            'usersRatingIDs' => $this->text(),
        ]);

        $this->createTable('social_event', [
            'id' => $this->primaryKey(),
            'idUser' => $this->integer()->notNull(),
            'idLike' => $this->integer()->notNull(),
            'pid' => $this->integer()->notNull(),
            'type' => 'social_type NOT NULL',
            'typeEvent' => 'social_type_event NOT NULL',
            'dateCreate' => $this->timestamp(),
        ]);

        $this->createIndex('social_pt', 'social', 'pid,type', true);
        $this->createIndex('social_event_pt', 'social_event', 'pid,type,idUser', true);
        $this->createIndex('social_event_iL', 'social_event', 'idLike');
        $this->createIndex('social_event_iU', 'social_event', 'idUser');
    }

    private function addBusiness()
    {
        $this->createTable('business', [
            'id' => $this->primaryKey(),
            'title' => $this->text()->notNull(),
            'idCity' => 'INT NOT NULL DEFAULT 1',
            'idUser' => $this->integer()->notNull(),
            'idCategories' => 'INT[] NOT NULL',
            'description' => $this->text(),
            'site' => $this->string(),
            'phone' => $this->string(),
            'urlVK' => $this->string(),
            'urlFB' => $this->string(),
            'urlTwitter' => $this->string(),
            'email' => $this->string(),
            'image' => $this->string(),
            'idProductCategories' => 'INT[]',
            'dateCreate' => $this->timestamp(),
            'isChecked' => $this->integer()->notNull()->defaultValue(0),
            'type' => $this->integer(),
            'ratio' => $this->integer()->defaultValue(1),
            'skype' => $this->string(100),
            'total_rating' => $this->integer(),
            'quantity_rating' => $this->integer(),
            'url' => $this->string(),
            'seo_description' => $this->text(),
            'seo_keywords' => $this->text(),
            'seo_title' => $this->text(),
            'dateUpdate' => $this->date()->defaultExpression('NOW()'),
            'sitemap_en' => "en_type DEFAULT '0'",
            'sitemap_priority' => $this->string(3),
            'sitemap_changefreq' => $this->string(),
        ]);
        $this->createIndex('business-user-id-index', 'business', 'idUser');

        $this->createTable('business_category', [
            'id' => $this->primaryKey(),
            'pid' => $this->integer(),
            'title' => $this->text()->notNull(),
            'image' => $this->string(),
            'lft' => $this->integer(),
            'rgt' => $this->integer(),
            'root' => $this->integer(),
            'depth' => $this->integer(),
            'seo_description' => $this->text(),
            'seo_keywords' => $this->string(),
            'seo_title' => $this->string(),
            'dateUpdate' => $this->date()->defaultExpression('NOW()'),
            'sitemap_en' => "en_type DEFAULT '0'",
            'sitemap_priority' => $this->string(3),
            'sitemap_changefreq' => $this->string(),
        ]);
        $this->createIndex('bc_pid', 'business_category', 'pid');

        $this->createTable('business_time', [
            'id' => $this->primaryKey(),
            'idBusiness' => $this->integer()->notNull(),
            'weekDay' => $this->integer()->notNull(),
            'start' => $this->time(),
            'end' => $this->time(),
        ]);
        $this->createIndex('bt_idBusinessWeek', 'business_time', 'idBusiness, weekDay');

        $this->createTable('business_address', [
            'id' => $this->primaryKey(),
            'idBusiness' => $this->integer()->notNull(),
            'lat' => $this->double()->notNull(),
            'lon' => $this->double(),
            'address' => $this->text()->notNull(),
            'phone' => $this->string(),
            'working_time' => $this->string(),
        ]);
        $this->createIndex('ba_idBusiness', 'business_address', 'idBusiness');
    }

    private function addProduct()
    {
        $this->createTable('product_category', [
            'id' => $this->primaryKey(),
            'title' => $this->text()->notNull(),
            'image' => $this->string(),
            'lft' => $this->integer(),
            'rgt' => $this->integer(),
            'root' => $this->integer(),
            'depth' => $this->integer(),
            'seo_description' => $this->text(),
            'seo_keywords' => $this->text(),
            'seo_title' => $this->text(),
            'dateUpdate' => $this->date()->defaultExpression('NOW()'),
            'sitemap_en' => "en_type DEFAULT '0'",
            'sitemap_priority' => $this->string(3),
            'sitemap_changefreq' => $this->string(),
        ]);

        $this->createIndex('pc_lft', 'product_category', 'lft');
        $this->createIndex('pc_rgt', 'product_category', 'rgt');
        $this->createIndex('pc_root', 'product_category', 'root');
        $this->createIndex('pc_level', 'product_category', 'level');
        $this->createIndex('bic_pid', 'product_category', 'pid');

        $this->createTable('product_company', [
            'id' => $this->primaryKey(),
            'title' => $this->string()->notNull(),
            'image' => $this->string(),
        ]);

        $this->createTable('product_customfield', [
            'id' => $this->primaryKey(),
            'idCategory' => $this->integer()->notNull(),
            'title' => $this->text()->notNull(),
            'alias' => $this->string(),
            'type' => $this->integer()->notNull()->defaultValue(0),
            'isFilter' => $this->integer()->notNull()->defaultValue(1),
        ]);

        $this->createTable('product_customfield_value', [
            'id' => $this->primaryKey(),
            'idCustomfield' => $this->integer()->notNull(),
            'value' => $this->string()->notNull(),
        ]);
    }

    public function down()
    {
        $this->dropTable('post');
        $this->dropTable('post_category');
        $this->dropTable('social');
        $this->dropTable('social_event');
        $this->dropTable('business');
        $this->dropTable('business_category');
        $this->dropTable('business_time');
        $this->dropTable('business_address');
        $this->dropTable('product_category');
        $this->dropTable('product_company');
        $this->dropTable('product_customfield');
        $this->dropTable('product_customfield_value');
    }
}
