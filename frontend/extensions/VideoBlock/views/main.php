<?php
/**
 * @var $this \yii\web\View
 * @var $config string
 * @var $categories array
 * @var $cities array
 * @var $selected integer
 */
?>

<div class="video-block-content">
    <div id="video-block">
        <div class="pattern"></div>

        <div class="pf-container">
            <div class="pf-row">
                <div class="wpb_column col-lg-12 col-md-12">
                    <div class="vc_column-inner">
                        <div class="wpb_wrapper">
                            <div class="pointfinder-mini-search">

                                <?= $this->render('_form', ['categories' => $categories, 'cities' => $cities, 'selected' => $selected]) ?>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="video" class="player" data-property="<?= $config ?>"></div>
</div>