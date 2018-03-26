<?php
/**
 * @var $this \yii\web\View
 * @var $public_id string
 */
?>

<div class="block">
    <div id="vk_groups"></div>
    
    <script type="text/javascript">
        var config = {
            mode: 4,
            wide: 1,
            width: 'auto',
            height: '400',
            color1: '282828',
            color2: 'FFFFFF',
            color3: '#0aab9a'
        };

        VK.Widgets.Group('vk_groups', config, <?= $public_id ?>);
    </script>
</div>