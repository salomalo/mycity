<?php
namespace console\controllers;

use Yii;
use yii\console\Controller;
use common\models\Product;

class ProductXmlController extends Controller
{
    public function actionIndex()
    {
        //$collection = Yii::$app->mongodb->getCollection('product');
        $collection = Product::find()->all();
        
        $count = 1;
        
        echo ('<?xml version="1.0" encoding="utf-8"?>

            <sphinx:docset>

            <sphinx:schema>
                <sphinx:field name="idProduct" attr="string" />
                <sphinx:field name="idCategory" attr="string" />
                <sphinx:field name="title" attr="string" /> 
            </sphinx:schema>
                
            ');
    
        foreach ($collection as $item): ?>
            
            <sphinx:document id="<?=$count?>">
                <idProduct><?=$item->_id?></idProduct>
                <idCategory><?=$item->idCategory?></idCategory>
                <title><?=$item->title?></title>
            </sphinx:document>
        
        <?php $count++ ?>
        <?php endforeach;
        
        echo('</sphinx:docset>');
    }
}

