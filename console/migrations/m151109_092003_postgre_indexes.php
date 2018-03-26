<?php

class m151109_092003_postgre_indexes extends yii\db\Migration
{
    public function safeUp()
    {
        $this->createIndex('action_indexes', 'action', ['idCompany', 'idCategory', 'url "text_pattern_ops"']);

        $this->createIndex('action_indexes_category', 'action_category', ['url "text_pattern_ops"']);
        
        $this->createIndex('afisha_indexes', 'afisha', ['idCategory', 'url "text_pattern_ops"']);
        $this->db->createCommand('CREATE INDEX afisha_indexes_idscompany on "afisha" USING GIN ("idsCompany");')->execute();
        
        $this->createIndex('afisha_indexes_category', 'afisha_category', ['url "text_pattern_ops"']);
        
        $this->createIndex('business_indexes_idcity_url', 'business', ['idCity', 'url "text_pattern_ops"']);
        $this->db->createCommand('CREATE INDEX business_indexes_idcategories on "business" USING GIN ("idCategories");')->execute();

        $this->createIndex('business_address_indexes', 'business_address', ['idBusiness']);
        $this->createIndex('business_category_indexes', 'business_category', ['url "text_pattern_ops"']);
        $this->createIndex('business_time_indexes', 'business_time', ['idBusiness']);
        $this->createIndex('city_indexes', 'city', ['idRegion', 'subdomain "text_pattern_ops"']);
        $this->createIndex('comment_indexes', 'comment', ['idUser', 'type', 'pid']);
        $this->createIndex('count_views_indexes', 'count_views', ['type', 'pid', '"pidMongo" "text_pattern_ops"']);
        $this->createIndex('gallery_indexes', 'gallery', ['type', 'pid']);
        $this->createIndex('orders_ads_indexes', 'orders_ads', ['idBusiness']);
        $this->createIndex('product_category_indexes', 'product_category', ['url "text_pattern_ops"']);
        $this->createIndex('product_customfield_indexes', 'product_customfield', ['idCategory', 'idCategoryCustomfield', 'alias "text_pattern_ops"']);
        $this->createIndex('product_customfield_value_indexes', 'product_customfield_value', ['idCustomfield']);
        $this->createIndex('schedule_kino_indexes', 'schedule_kino', ['idAfisha', 'idCompany', 'idCity']);
    }

    public function safeDown()
    {
        $this->dropIndex('action_indexes', 'action');

        $this->dropIndex('action_indexes_category', 'action_category');
        
        $this->dropIndex('afisha_indexes', 'afisha');
        $this->dropIndex('afisha_indexes_idscompany', 'afisha');
        
        $this->dropIndex('afisha_indexes_category', 'afisha_category');
        
        $this->dropIndex('business_indexes_idcity_url', 'business');
        $this->dropIndex('business_indexes_idcategories', 'business');
        
        $this->dropIndex('business_address_indexes', 'business_address');
        
        $this->dropIndex('business_category_indexes', 'business_category');
        
        $this->dropIndex('business_time_indexes', 'business_time');
        
        $this->dropIndex('city_indexes', 'city');
        
        $this->dropIndex('comment_indexes', 'comment');
        
        $this->dropIndex('count_views_indexes', 'count_views');
        
        $this->dropIndex('gallery_indexes', 'gallery');
        
        $this->dropIndex('orders_ads_indexes', 'orders_ads');
        
        $this->dropIndex('product_category_indexes', 'product_category');
        
        $this->dropIndex('product_customfield_indexes', 'product_customfield');
        
        $this->dropIndex('product_customfield_value_indexes', 'product_customfield_value');
        
        $this->dropIndex('schedule_kino_indexes', 'schedule_kino');
        
        $this->dropIndex('work_category_indexes', 'work_category');
        
        $this->dropIndex('work_resume_indexes', 'work_resume');
        
        $this->dropIndex('work_vacantion_indexes', 'work_vacantion');
    }
}
