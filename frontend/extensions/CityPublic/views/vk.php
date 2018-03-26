<?php
/**
 * @var common\models\WidgetCityPublic $widget
 */
?>

<div id="vk_groups"></div>
<script type="text/javascript">
    VK.Widgets.Group(
        "vk_groups", {
            mode: 0,
            wide: 0,
            width: "<?= $widget->width ?>",
            height: "<?= $widget->height ?>",
            color1: 'FFFFFF',
            color2: '2B587A',
            color3: '5B7FA6'
        }, '<?= $widget->group_id ?>');
</script>