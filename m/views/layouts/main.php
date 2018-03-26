<?php
use m\assets\AppAsset;
use yii\helpers\Html;

/**
 * @var \yii\web\View $this
 * @var string $content
 */
AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= Html::encode($this->title) ?></title>
    <script src="https://maps.googleapis.com/maps/api/js?v=3.exp"></script>

    <?php $this->head() ?>

</head>
<body>
    <?php $this->beginBody() ?>
    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"></div>
    <div class="col-xs-10 col-xs-offset-1 col-sm-8 col-sm-offset-2 col-md-6 col-md-offset-3 ">
        <?= $content;?>
    </div>
    <?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
