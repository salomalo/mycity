<?php
/**
 * @var $this \yii\web\View
 * @var $model \common\models\Business
 */
use common\models\Action;
use yii\helpers\Html;

$timeZone = new \DateTimeZone(Yii::$app->params['timezone']);
$now = new DateTime();
$date = time();

$now->setTimezone($timeZone);
$now->setTimestamp($date);
$nowDate = $now->format('Y-m-d 00:00:00');
$dateTwo = $now->modify('+1 day')->format('Y-m-d 00:00:00');

$actions = Action::find()
    ->where(['idCompany' => $model->id])
    ->andWhere(['>=', 'dateEnd', $nowDate])
    ->andWhere(['<=', 'dateStart', $dateTwo])
    ->groupBy(['action."dateStart"', 'action."id"'])
    ->orderBy(['action."dateStart"' => SORT_ASC])
    ->limit(12)
    ->all();
?>

<?php if ($actions) : ?>
    <div class="listing-detail-section">
        <div class="listing-detail-section">
            <h2 class="page-header">
                <?= Yii::t('action', 'Promotions') ?>
            </h2>
            <div class="b-product-line b-product-line_size_wide js-gallery-container">
                <?php foreach ($actions as $action) : ?>
                    <?= $this->render('_short_action', ['model' => $action, 'businessModel' => $model]) ?>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
<?php endif; ?>

