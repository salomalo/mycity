<?php
/**
 * @var \yii\web\View $this
 * @var string $content
 */

use office\assets\AppAsset;
use yii\helpers\Html;

AppAsset::register($this);
?>

<?php $this->beginPage() ?>
<!DOCTYPE html>

<html lang="<?= Yii::$app->language ?>">

    <head>
        <meta charset="<?= Yii::$app->charset ?>"/>
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title><?= Html::encode($this->title) ?></title>

        <link rel="stylesheet" type="text/css" href="/css/style.css" />

        <?php $this->head() ?>
    </head>

    <body class="skin-blue">
        <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"></div>
        <?php $this->beginBody() ?>

        <?= $content ?>

        <?php $this->endBody() ?>
    </body>

</html>
<?php $this->endPage() ?>