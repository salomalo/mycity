<div class="time">
    <?php if($this->context->model->dateStart && $this->context->model->dateEnd):?>
        <?php $remainingTime = $this->context->model->getRemainingTime($this->context->model->dateStart, $this->context->model->dateEnd); ?>
        <span><?= Yii::t('action', 'The_offer_is_valid')?></span>
        <span class="period">
                    <?php if($remainingTime['start']->format('d.m.Y') != $remainingTime['end']->format('d.m.Y')):?>
                        с <?= $remainingTime['start']->format('d.m.Y') ?> по <?= $remainingTime['end']->format('d.m.Y') ?>
                    <?php else:?>
                        <?= $remainingTime['start']->format('d.m.Y') ?>
                    <?php endif;?>
                </span>
        <span class="ostatok-index <?= $remainingTime['style']?>">
                    <?php if(!$remainingTime['interval']->invert):?>
                        <img src="img/icons/bomb.png" alt="" />
                        <?= (!empty($remainingTime['left'])) ? $remainingTime['left'] : '' ?>
                    <?php endif;?>
                </span>
    <?php endif;?>
</div>