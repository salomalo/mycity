<?php
/**
 * @var $this \yii\web\View
 * @var $categories array
 * @var $cities array
 * @var $selected integer
 */

use yii\helpers\Html;
?>

<?= Html::beginForm(['search/index'], 'get', ['id' => 'pointfinder-search-form-manual', 'class' => 'pfminisearch']) ?>

    <div class="pfsearch-content golden-forms">
        <div class="pf-row">

            <div class="col-lg-3 col-md-3 col-sm-3 colhorsearch">
                <div id="jobskeyword_main" class="ui-widget">
                    <label class="lbl-ui pflabelfixsearch pflabelfixsearchjobskeyword">
                        <?= Html::input('text', 's', null, ['id' => 'jobskeyword', 'class' => 'input', 'placeholder' => Yii::t('widgets', 'Keyword')]) ?>
                    </label>
                </div>
            </div>

            <div class="col-lg-3 col-md-3 col-sm-3 colhorsearch">
                <?= $this->render('_city', ['cities' => $cities]) ?>
            </div>

            <div class="col-lg-3 col-md-3 col-sm-3 colhorsearch">
                <?= $this->render('_category', ['categories' => $categories, 'selected' => $selected]) ?>
            </div>

            <div class="col-lg-3 col-md-3 col-sm-3 colhorsearch">
                <button type="submit" class="button pfsearch" id="pf-search-button-manual">
                    <?= Yii::t('widgets', 'Search') ?>
                </button>
            </div>

        </div>
    </div>

<?= Html::endForm() ?>