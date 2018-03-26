<?php
/**
 * @var common\models\WidgetCityPublic $widget
 */
?>

<div id="fb-root"></div>
<div class="fb-page"
     data-href= "<?= $widget->group_id ?>"
     data-tabs="timeline"
     data-width="<?= $widget->width ?>"
     data-height="<?= $widget->height ?>"
     data-small-header="true"
     data-adapt-container-width="true"
     data-hide-cover="true"
     data-show-facepile="true"
>
<div class="fb-xfbml-parse-ignore">
    <blockquote cite="<?= $widget->group_id ?>"><a href="<?= $widget->group_id ?>">Citylife</a></blockquote>
</div>
</div>