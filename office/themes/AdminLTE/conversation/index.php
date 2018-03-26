<?php
/**
 * @var \yii\web\View $this
 * @var \common\models\QuestionConversation[] $conversations
 * @var $business common\models\Business
 */
use yii\helpers\Html;

$this->title = $business->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('business', 'Business'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$link = 'http://' . $business->getSubDomain() . Yii::$app->params['appFrontend'] . $business->getFrontendUrl();
$btnSeeOnFrontend = Html::a('Посмотреть на сайте', $link, ['class' => 'btn bg-purple', 'target' => '_blank', 'style' => 'margin-left: 20px']);

$script = <<< JS
    $('.right-side .content-header h1').append('$btnSeeOnFrontend');
JS;
$this->registerJs($script, yii\web\View::POS_READY);

?>

<div class="row">
    <div class="box box-info">
        <div class="box-header with-border"><h3 class="box-title">Вопросы</h3></div>
        <div class="box-body">
            <div class="table-responsive">
                <table class="table no-margin">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>Название</th>
                        <th>От кого</th>
                        <th>Статус</th>
                        <th>Дата последнего ответа</th>
                    </tr>
                    </thead>
                    <tbody>

                    <?php foreach ($conversations as $conversation) : ?>
                        <tr>
                            <td>
                                <?php
                                echo $conversation->id
                                ?>
                            </td>
                            <td>
                                <?php
                                echo Html::a($conversation->title, ['conversation/view', 'conversation_id' => $conversation->id])
                                ?>
                            </td>
                            <td>
                                <?php
                                echo $conversation->user ? Html::a($conversation->user->username, ['conversation/view', 'conversation_id' => $conversation->id]) : ''
                                ?>
                            </td>
                            <td>
                                <?php
                                echo $conversation->statusLabelHtml
                                ?>
                            </td>
                            <td>
                                <?php
                                echo $conversation->lastQuestionDate
                                ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
