<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;
?>


<div class="block-title"><?= Yii::t('product', 'Choose_the_parameters')?> <i class="fa fa-long-arrow-down"></i></div>
<div class="block-content filter-tovars">  
    <div class="block-filter">
        <?php 
                $form = ActiveForm::begin([
                'id' => 'filter-form-inline', 
                'method'=>'GET',
                'action'=>$action,
                'options' => ['class' => 'form-horizontal',],
                'fieldConfig' => [
                'template' => '{input}',
                
    ],
            ]); 
        ?>

        <div class="price_filter">
        <div class="title"><?= Yii::t('product', 'Price_range')?></div>
        
        <?= $form->field($model, 'minPrice')->textInput(['class'=>'form-control form-price'])->label(false) ?> 
        <div class="tire">-</div>
        <?= $form->field($model, 'maxPrice')->textInput(['class'=>'form-control form-price'])->label(false) ?> 
        <div class="price">грн.</div>
        <?= Html::submitButton('', ['class' => 'btn btn-primary ']) ?>
        <div class="clear"></div>
        <input type="text" id="range" value="" name="range" onkeypress='return event.charCode >= 48 && event.charCode <= 57' />
        <div class="clear"></div>
        </div> 
        <?php
//        echo "<pre>";
//        print_r($model);
//        echo "</pre>";
           $get = Yii::$app->request->get();
           if (!empty($get['FilterForm']['companies'])){
           foreach ($get['FilterForm']['companies'] as $key=>$value){
             $model->companies[$value] = $value;  
           }
           }
        ?>
        <?php if(count($companies)>0): ?>
        <?php $selected=0; if(!empty($model->companies)){ $selected = $model->companies;}?> 
        <div class="fields_block">
            <div class="title"><?= Yii::t('product', 'Companies')?> </div>
            <?php if(count($companies)>10): ?>
                  <?php  $values = array_slice( $companies, 0,10,true); ?>
                  <?=Html::checkboxList('FilterForm[companies][]', $selected, $values)?>
                <div class="fields_hide">   
                  <?php  $values = array_slice( $companies, 10,null,true); ?>
                  <?=Html::checkboxList('FilterForm[companies][]', $selected, $values)?>
                </div>             
                <div class="more_field"> → <span><?= Yii::t('product', 'Show_all_options')?></span></div>
            <?php else:?>
                 <?php if(isset($values)): ?>
                  <?=Html::checkboxList('FilterForm[companies][]', $selected, $values)?>
                 <?php endif;?>
            <?php endif; ?>
                
            <span class="bottomline"></span>    
        </div>          
        <?php endif; ?>
        
        <?php foreach ($filter as $item): ?>
        <div class="fields_block">
            <div class="title"><?= $item['title']. ' :'; ?></div>
            <?php 
                if(!empty($item['selVal'])){
                    $model->field[$item['alias']] = $item['selVal'];      
                }
            ?>
                <?php $selected=0; if(!empty($model->field[$item['alias']])){ $selected = $model->field[$item['alias']];}
                ?> 
            <?php if(count($item['value'])>10): ?>
                <?php   $values = array_slice( $item['value'],0,10,true); ?>
              <?=Html::checkboxList('FilterForm[field]['. $item['alias'] .']', $selected, $values)?>
                <div class="fields_hide">   
                  <?php  $values = array_slice( $item['value'], 10,null,true); ?>
              <?=Html::checkboxList('FilterForm[field]['. $item['alias'] .']', $selected, $values)?>
                </div>    
                <div class="more_field"> → <span><?= Yii::t('product', 'Show_all_options')?></span></div>
      
            <?php else: ?>
                <?= $form->field($model, 'field['. $item['alias'] .']')->checkboxList($item['value']); ?>
            <?php endif ?>
         <span class="bottomline"></span>    
        </div>                        
        <?php endforeach?>
        <?php ActiveForm::end(); ?>
    </div>
</div>