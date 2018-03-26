<?php

use common\models\ProductCategory;
use yii\db\Migration;

class m170220_144759_fixed_product_category extends Migration
{
    public function up()
    {
        $listRootCategory = [
            196,
            2375,
        ];

        foreach ($listRootCategory as $category) {
            $query = ProductCategory::find()
                ->where(['root' => $category])
                ->andWhere('id != :id', ['id' => $category])
                ->groupBy(['lft', 'id'])
                ->orderBy(['lft' => SORT_ASC]);
            /** @var ProductCategory[] $nodes */
            $nodes = $query->all();

            $left = 1;
            /** @var ProductCategory $root */
            $root = ProductCategory::findOne($category);
            if ($root) {
                $root->depth = 0;
                $root->lft = $left;

                foreach ($nodes as $node) {
                    $node->depth = 1;
                    $node->lft = $left + 1;
                    $node->rgt = $left + 2;

                    $node->save();
                    $left += 2;
                }

                $root->rgt = (count($nodes) + 1) * 2;
                $root->save();
            }
        }
    }

    public function down()
    {

    }

    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }

    public function safeDown()
    {
    }
    */
}
