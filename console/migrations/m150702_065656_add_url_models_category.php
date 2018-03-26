<?php

class m150702_065656_add_url_models_category extends yii\db\Migration
{
    public function up()
    {
        $this->addColumn('afisha_category','url','varchar(255) DEFAULT NULL');
        $this->addColumn('business_category','url','varchar(255) DEFAULT NULL');
        $this->addColumn('post_category','url','varchar(255) DEFAULT NULL');    
        $this->addColumn('product_category','url','varchar(255) DEFAULT NULL');    
        $this->addColumn('action_category','url','varchar(255) DEFAULT NULL');
        $this->addColumn('work_category','url','varchar(255) DEFAULT NULL');

    }

    public function down()
    {
        echo "m150702_065656_add_url_models_category cannot be reverted.\n";

        return false;
    }
}
