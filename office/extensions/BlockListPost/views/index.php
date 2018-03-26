<?php
/**@var $models \yii\db\ActiveRecord[] */
/**@var $this yii\base\View */
?>


<div class="widget widget_categories">
    <div class="widget-bgr"><div class="bgr">
            <div class="title">
                <div>
                    <div>
                        <h2><?=$this->context->title?></h2>
                    </div>
                </div>
            </div>
<?php 
    if(is_array($this->context->addButton)){
        foreach ($this->context->addButton as $item){
            echo $item;
        }
    }
?>

            <ul>
                <?php foreach ($models as $model): ?>
                    <li class="cat-item"><a href="<?=$this->context->createUrl($model->id);?>" title=""><?=$model->title?></a>
                <?php endforeach;?>
            </ul>

        </div>
    </div>
</div>