<?php

class m151113_112034_work_resume_meta extends yii\db\Migration
{
    public function up()
    {
        $this->addColumn('work_category','seo_title_resume', $this->text());
        $this->addColumn('work_category','seo_description_resume', $this->text());
        $this->addColumn('work_category','seo_keywords_resume', $this->text());
    }

    public function down()
    {
        echo "m151113_112034_work_resume_meta cannot be reverted.\n";

        return false;
    }
}
