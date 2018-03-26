<?php
use yii\widgets\ListView;
use yii\widgets\Pjax;
?>

<?php if($title && $dataProvider->count):?>
    <h4 class="predpr-akcii"><?= $title ?></h4>
    <br>
<?php endif;?>

<?php
Pjax::begin(['id' => 'business-actions']);
echo ListView::widget([
    'dataProvider' => $dataProvider,
    'emptyText' => '',
    'layout' => '{items}{pager}',
    'itemView' => '_action_short',
    'pager' => [
//        'class' => 'yii\widgets\LinkPager',
        'class' => 'frontend\extensions\MyLinkPager\MyLinkPager',
    ],
    'itemOptions' => ['class' => 'item'],
    'viewParams' => [
//        'pid' => $pid,
    ],
]);
Pjax::end();

