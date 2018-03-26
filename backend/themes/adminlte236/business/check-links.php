<?php
/**
 * @var $business \common\models\Business
 * @var $afisha \common\models\Afisha[]
 */
use yii\helpers\Html;
use yii\helpers\Url;

?>
<p>
    <?= Html::a('Просмотр', ['view', 'id' => $business->id], ['class' => 'btn btn-success']) ?>
    <?= Html::a('Список', ['index'], ['class' => 'btn btn-info']) ?>
</p>
<div class="row">
    <div class="col-xs-9">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title">Связанные элементы с <?= $business->title ?></h3>
            </div>
            <div class="box-body table-responsive no-padding">
                <table class="table table-hover">
                    <tbody>
                    <tr>
                        <th>ID</th>
                        <th>Раздел</th>
                        <th>Название</th>
                    </tr>

                    <tr>
                        <td><?= $business->id ?></td>
                        <td><span class="label label-info">Предприятие</span></td>
                        <td><?= $business->title ?></td>
                    </tr>

                    <?php foreach ($business->actions as $element) : ?>
                        <tr>
                            <td><?= $element->id ?></td>
                            <td><span class="label label-success"><span class="fa fa-shopping-cart"></span> Акция</span></td>
                            <td><?= $element->title ?></td>
                        </tr>
                    <?php endforeach; ?>
                    
                    <?php foreach ($business->ads as $element) : ?>
                        <tr>
                            <td><?= $element->_id ?></td>
                            <td><span class="label label-success"><span class="fa fa-file-text-o"></span> Объявление</span></td>
                            <td><?= $element->title ?></td>
                        </tr>
                    <?php endforeach; ?>

                    <?php foreach ($business->scheduleKino as $element) : ?>
                        <tr>
                            <td><?= $element->id ?></td>
                            <td><span class="label label-success"><span class="fa fa-film"></span> Расписание</span></td>
                            <td><?= $element->afisha ? $element->afisha->title : '' ?></td>
                        </tr>
                    <?php endforeach; ?>

                    <?php foreach ($afisha as $element) : ?>
                        <tr>
                            <td><?= $element->id ?></td>
                            <td><span class="label label-success"><span class="fa fa-calendar-check-o"></span> Афиша</span></td>
                            <td><?= $element->title ?></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <div class="box-footer clearfix">
                <a href="<?= Url::to(['delete-with-links', 'id' => $business->id]) ?>"
                   class="btn btn-sm btn-danger btn-flat pull-right"
                   data-method="post"
                   data-confirm="Вы уверены что хотите удалить предприятие и весь связанный контент?"
                >
                    <span class="fa fa-warning"></span> Удалить всё
                </a>
            </div>
        </div>
    </div>
</div>
