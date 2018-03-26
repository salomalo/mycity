<?php
use yii\helpers\Html;

$langUrl = Yii::$app->request->getLangUrl();
$class = [
    'ru' => ((Yii::$app->language === 'ru-RU') ? 'active' : null),
    'uk' => ((Yii::$app->language === 'uk-Uk') ? 'active' : null),
];
?>

<div class="language-widget">
    <div class="text">
        <?= Yii::t('app', 'language_site') ?>
    </div>
    <?= Html::a(Html::img('/img/ru.png', ['width' => 17 * 2, 'height' => 12 * 2, 'style' => 'margin-right: 20px;']), "/ru{$langUrl}", ['class' => $class['ru'] ? 'active' : '']) ?>
    <?= Html::a(Html::img('/img/ua.png', ['width' => 17 * 2, 'height' => 12 * 2]), "/uk{$langUrl}", ['class' => $class['uk'] ? 'active' : '']) ?>
</div>
