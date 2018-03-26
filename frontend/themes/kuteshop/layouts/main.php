<?php
/**
 * @var $content
 * @var \yii\web\View $this
 */

use frontend\themes\kuteshop\AppAssets;

AppAssets::register($this);
$model = $this->context->businessModel;
?>
<body class="index-opt-1 catalog-product-view catalog-view_op1">
<div class="wrapper">

    <?= $this->render('main/header', ['businessModel' => $model]) ?>

    <?= $content ?>

<!--    --><?php
//    ?>

    <a href="#" class="back-to-top">
        <i aria-hidden="true" class="fa fa-angle-up"></i>
    </a>
</div>
</body>
