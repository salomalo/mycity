<?php

class m151203_083228_show_post extends yii\db\Migration
{
    public function up()
    {
        $this->update('post_category', ['sitemap_en' => 1]);
        $this->db->createCommand("ALTER TABLE post_category ALTER COLUMN sitemap_en SET DEFAULT '1'")->execute();
        
        
        
        $this->update('post', ['sitemap_en' => 1]);
        $this->db->createCommand("ALTER TABLE post ALTER COLUMN sitemap_en SET DEFAULT '1'")->execute();
        
        $this->addColumn('post', 'allCity', $this->boolean());
        $this->addColumn('post', 'onlyMain', $this->boolean());
    }

    public function down()
    {
        echo "m151203_083228_show_post cannot be reverted.\n";

        return false;
    }
}
