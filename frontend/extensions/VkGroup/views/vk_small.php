<?php
/**
 * @var $this \yii\web\View
 * @var $public_id string
 */
?>
<div class="row">
    <div class="col-sm-3"></div>
    <div class="col-sm-3"></div>
    <div class="col-sm-3"></div>

    <div class="col-sm-3">
        <div id="vk_groups" style="margin-top: 20px"></div>
    </div>
</div>

<script type="text/javascript">
    var config = {
        redesign: 1,
        mode: 3,
        width: "400",
        color1: 'FFFFFF',
        color2: '000000',
        color3: '5E81A8'
    };

    VK.Widgets.Group('vk_groups', config, <?= $public_id ?>);
</script>