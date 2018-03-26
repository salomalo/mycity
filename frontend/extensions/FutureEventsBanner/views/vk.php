<?php
/**
 * @var $this \yii\web\View
 * @var $public_id string
 */
use yii\helpers\Html;

?>
<div class="block">
    <div>
        <div class="block-title btn-reklama">
            <?= Html::a('Заказать рекламу', '/ru', ['onclick' => 'contact("/ru/site/showmodal-contact?type=reklama");return false;']) ?>
        </div>
        <div id="vk_group_small"></div>
    </div>
    <script type="text/javascript">
        VK.Widgets.Group('vk_group_small', {redesign: 1, mode: 3, width: "239", height: "186", color1: 'FFFFFF', color2: '000000', color3: '5E81A8'}, <?= $public_id ?>);
    </script>
</div>