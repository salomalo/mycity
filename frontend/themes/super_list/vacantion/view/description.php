<?php
/**
 * @var $this \yii\web\View
 * @var $model \common\models\Business
 */
use yii\helpers\Html;

?>

<div class="listing-detail-section" id="listing-detail-section-contact">
    <h2 class="page-header"><?= Yii::t('vacantion', 'Details_of_the_job') ?></h2>

    <div class="listing-detail-contact">
        <div class="row">
            <div class="col-md-8">
                <ul>
                    <li class="title">
                        <strong class="key"><?= Yii::t('vacantion', 'Where_to_look_for_an_employee') ?></strong>
                        <span class="value"><?= $model->city->title ?></span>
                    </li>

                    <li class="working-conditions">
                        <strong class="key"><?= Yii::t('vacantion', 'Working_conditions') ?></strong>
                        <span class="value">
                            <?= ($model->isFullDay) ? Yii::t('vacantion', 'Full_day') : Yii::t('vacantion', 'Not_a_full_day') ?>
                            ,
                            <?= ($model->isOffice) ? Yii::t('vacantion', 'office_work') : Yii::t('vacantion', 'work_remotely') ?>
                        </span>
                    </li>

                    <li class="sex">
                        <strong class="key"><?= Yii::t('vacantion', 'We_are_looking_for') ?></strong>
                        <span class="value">
                            <?= ($model->male) ? Yii::t('vacantion', 'man') : Yii::t('vacantion', 'woman') ?>
                        </span>
                    </li>

                    <?php if ($model->minYears || $model->maxYears): ?>
                        <li class="age">
                            <strong class="key"><?= Yii::t('vacantion', 'Age') ?></strong>
                            <span class="value">
                                <?= ($model->minYears) ? Yii::t('vacantion', 'from') . ' ' . $model->minYears : '' ?>
                                <?= ($model->maxYears) ? Yii::t('vacantion', 'before') . ' ' . $model->maxYears : '' ?>
                            </span>
                        </li>
                    <?php endif; ?>

                    <?php if ($model->experience): ?>
                        <li class="experience">
                            <strong class="key"><?= Yii::t('vacantion', 'Required_experience') ?></strong>
                            <span class="value">
                                <?= $model->experience ?>
                            </span>
                        </li>
                    <?php endif; ?>

                    <?php if ($model->education): ?>
                        <li class="education">
                            <strong class="key"><?= Yii::t('vacantion', 'Education') ?></strong>
                            <span class="value">
                                <?= $model->educationList[$model->education] ?>
                            </span>
                        </li>
                    <?php endif; ?>

                    <?php if ($model->salary): ?>
                        <li class="salary">
                            <strong class="key"><?= Yii::t('vacantion', 'We_offer_salary') ?></strong>
                            <span class="value">
                                <?= $model->salary ?>
                            </span>
                        </li>
                    <?php endif; ?>

                    <?php if ($model->phone): ?>
                        <li class="phone">
                            <strong class="key"><?= Yii::t('vacantion', 'Phone') ?></strong>
                            <span class="value">
                                <?= $model->phone ?>
                            </span>
                        </li>
                    <?php endif; ?>

                    <?php if ($model->email): ?>
                        <li class="email">
                            <strong class="key"><?= Yii::t('vacantion', 'E-mail') ?></strong>
                            <span class="value">
                                <?= Html::mailto($model->email) ?>
                            </span>
                        </li>
                    <?php endif; ?>

                    <?php if ($model->skype): ?>
                        <li class="skype">
                            <strong class="key"><?= Yii::t('vacantion', 'Skype') ?></strong>
                            <span class="value">
                                <?= $model->skype ?>
                            </span>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </div>
</div>