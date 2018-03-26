<?php

namespace frontend\extensions\FilterProduct;

use frontend\models\FilterForm;
use common\models\ProductCompany;
use yii\helpers\ArrayHelper;
use common\models\Product;
use yii\helpers\Url;
/**
 * Description of FilterProduct
 *
 * @author dima
 */
class FilterProduct extends \yii\base\Widget
{
    public $min = 0;
    public $max = 5000;
    public $from = 0;
    public $to = 5000;
    public $filter;
    public $selectedPrice;
    public $id_product_category = null;
    public $alias_product_category = '';
    
    public function init()
    {
        if(!empty($this->selectedPrice)){
            $this->from = $this->selectedPrice[0];
            $this->to = $this->selectedPrice[1];
        }
    }  
    
    public function run()
    {
        $view = $this->getView();

        Assets::register($view);
        
        $js = '$(function () {
            minInput = $("#filterform-minprice").val('. $this->from .');
            maxInput = $("#filterform-maxprice").val('. $this->to .');
            
            $(document).on("keyup", "#filterform-minprice", function(e){
                var vint = parseInt(minInput.val()); 
                slider = $("#range");
              
                if( isNaN(vint) ){
                    return;
                }
                
                if( vint < 0 ){
                    vint = slider.data("from");
                }
                
                if( vint > '. $this->to .' ){
                    vint = slider.data("from");
                }
                
                minInput.val(vint)
                
                var slider = slider.data("ionRangeSlider");
                    slider.update({
                    from: minInput.val(),
                });
                
            });
            
            $(document).on("keyup", "#filterform-maxprice", function(e){
                var vint = parseInt(maxInput.val()); 
                slider = $("#range");
                
                if( isNaN(vint) ){
                    return;
                }
                
                if( vint < slider.data("from") ){
                    var sliderM = slider.data("ionRangeSlider");
                    sliderM.update({
                        to: slider.data("from"),
                    });
                    return;
                }
                
                if( vint > '. $this->to .' ){
                    vint = slider.data("to");
                }
                
                maxInput.val(vint)
                
                var slider = slider.data("ionRangeSlider");
                    slider.update({
                    to: maxInput.val(),
                });
            });
            
            $("#range").ionRangeSlider({
                hide_min_max: true,
                hide_min_max: true,
                hide_from_to: true,
                min: '. $this->min .',
                max: '. $this->max .',
                from: '. $this->from .',
                to: '. $this->to .',
                type: "double",
                step: 1,
                onChange: function (data) {
                    from = data["from"];
                    to = data["to"];
                    
                    minInput.val(from);
                    maxInput.val(to);
                    //console.log(from + " - " + to);
                }  
            });

        });';
        
        $view->registerJs($js);
        //$companiesids = Product::find()->collection->group(['idCompany'=>1], ['idCategory'=>955], 'function( curr, result ) {}',['idCategory'=>1]);

        //$companies = ProductCategory::find()->collection->gr
        $where =[]; 
        if (isset($this->id_product_category)){
//            $companies = Product::find()->collection->group(['idCompany'=>1], ['items'=>[]], 'function( curr, result ) {}',['idCategory'=>(int)$this->id_product_category]);
            $companies = Product::find()->where(['idCategory'=>(int)$this->id_product_category])->limit(1000)->all();
            foreach($companies as $item){
                if (! in_array($item->idCompany, $where)){
                   $where[] = $item->idCompany;
                }   
            }
            $action = Url::to(['product/index','pid'=>$this->alias_product_category]);
        } else {
            $action = Url::to(['product/index']);            
        }
        return $this->render('index', [
            'model' => new FilterForm(),
            'companies' => ArrayHelper::map(ProductCompany::find()->where(['id'=>$where])->orderBy('title ASC')->all(),'id','title'),
            'filter' => $this->filter,
            'action' => $action,
        ]);
    }
}
