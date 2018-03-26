<?php
/**
 * @var $this \yii\web\View
 * @var $models QuestionConversation[]
 */
use common\models\QuestionConversation;
use yii\helpers\Html;

?>
<div class="row">
    <div class="box box-info grid-view-border-top">
        <div class="box-header with-border"><h3 class="box-title">Поддержка</h3></div>
        <div class="box-body">
            <div class="table-responsive">
                <table class="table no-margin">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>Категория</th>
                        <th>Название</th>
                        <th>Статус</th>
                        <th>Дата последнего ответа</th>
                    </tr>
                    </thead>
                    <tbody>

                    <?php foreach ($models as $conversation) : ?>
                        <tr>
                            <td><?= $conversation->id ?></td>
                            <td><?= $conversation->typeLabel ?></td>
                            <td><?= Html::a($conversation->title, ['support/view', 'id' => $conversation->id]) ?></td>
                            <td><?= $conversation->statusLabelHtml ?></td>
                            <td><?= $conversation->lastQuestionDate ?></td>
                        </tr>
                    <?php endforeach; ?>

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
