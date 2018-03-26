<?php

class m161115_072735_add_column_type_to_business_rating extends yii\db\Migration
{
    public function up()
    {
        $this->renameTable('business_rating', 'star_rating');
        $this->renameColumn('star_rating', 'business_id', 'object_id');
        $this->alterColumn('star_rating', 'object_id', $this->string());
        $this->addColumn('star_rating', 'object_type', $this->integer());
    }
}
