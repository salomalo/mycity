<?php
/**
 * @var yii\web\View $this
 * @var common\models\search\ProductCustomfield $searchModel
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var array $customfields
 * @var integer $idCat
 */

use common\models\ProductCategory;
use kartik\widgets\Select2;
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Product Customfields';
$this->params['breadcrumbs'][] = $this->title;

/** @var ProductCategory[] $productCategories */
$productCategories  = ProductCategory::find()->roots()->all();

$out = [];
foreach ($productCategories as $category) {
    $out[$category->id] = $category->title;

    /** @var ProductCategory[] $children */
    $children = $category->children(1)->all();

    foreach ($children as $child) {
        $out[$child->id] = "-$child->title";

        /** @var ProductCategory[] $others */
        $others = $child->children()->all();

        foreach ($others as $other) {
            $out[$other->id] = "--$other->title";
        }
    }
}
?>

<?= Html::a('Create', ['create', 'category' => $idCat ? $idCat : null], ['class' => 'btn btn-success']) ?>

<br><br>

<?= Html::beginForm(['/product-customfield/index'], 'get') ?>
<?= Select2::widget([
    'name' => 'category',
    'data' => $out,
    'value' => $idCat,
    'options' => ['placeholder' => 'Select a category ...'],
    'pluginOptions' => ['allowClear' => true],
    'pluginEvents' => ['change' => "function () { $(this).closest('form').submit(); }"],
]) ?>
<?= Html::endForm() ?>

<br><br>

<?php foreach ($customfields as $customField) : ?>

    <?php $id = $customField['id']; ?>
    <div style="border: 1px solid #000000;padding: 5px; display: table; margin-bottom: 5px;">
        <div style="width: 300px; display: table-cell;"><?= $customField['customfieldCategory'] ?></div>
        <div style="width: 300px; display: table-cell;"><?= $customField['title'] ?></div>

        <div style="width: 350px; display: table-cell;">
            <?php foreach ($customField['value'] as $value) : ?>
                <?= '<div style="border-bottom: 1px dotted #ccc;">', $value, '</div>' ?>
            <?php endforeach; ?>
        </div>

        <div style="width: 100px; display: table-cell;">
            <?= Html::a('<span class="glyphicon glyphicon-pencil"></span>', ['product-customfield/update', 'id' => $id]) ?>

            <a href="<?= Url::to(['product-customfield/delete', 'id' => $id]) ?>" data-confirm="Are you sure you want to delete this item?" data-method="post">
                <span class="glyphicon glyphicon-trash"></span>
            </a>
        </div>
    </div>

<?php endforeach ?>