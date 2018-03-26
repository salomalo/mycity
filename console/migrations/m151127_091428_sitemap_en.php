<?php

class m151127_091428_sitemap_en extends yii\db\Migration
{
    public function safeUp()
    {
        $this->update('afisha_category', ['sitemap_en' => 1]);
        $this->db->createCommand("ALTER TABLE afisha_category ALTER COLUMN sitemap_en SET DEFAULT '1'")->execute();
        
        $this->update('afisha', ['sitemap_en' => 1]);
        $this->db->createCommand("ALTER TABLE afisha ALTER COLUMN sitemap_en SET DEFAULT '1'")->execute();

        $this->update('action_category', ['sitemap_en' => 1]);
        $this->db->createCommand("ALTER TABLE action_category ALTER COLUMN sitemap_en SET DEFAULT '1'")->execute();
        
        $this->update('action', ['sitemap_en' => 1]);
        $this->db->createCommand("ALTER TABLE action ALTER COLUMN sitemap_en SET DEFAULT '1'")->execute();
        
        $this->update('business_category', ['sitemap_en' => 1]);
        $this->db->createCommand("ALTER TABLE business_category ALTER COLUMN sitemap_en SET DEFAULT '1'")->execute();
        
        $this->update('business', ['sitemap_en' => 1], ['idCity' => Yii::$app->params['activeCitys']]);
        $this->db->createCommand("ALTER TABLE business ALTER COLUMN sitemap_en SET DEFAULT '1'")->execute();
    }

    public function down()
    {
        echo "m151127_091428_sitemap_en cannot be reverted.\n";

        return false;
    }
}
