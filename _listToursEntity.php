<?php
use yii\helpers\Html;
?>
<input type="hidden" class="tourName" value="<?=$model['name']?>">
<input type="hidden" class="date" value="<?=$model['isOpenDate']?$model['dayStart'] : $model['dayStart'] . ' ' . $model['monthStart']?>">
<input type="hidden" class="duration" value="<?=Yii::$app->i18n->messageFormatter->format(
                                        '{n, plural, one{# день} few{# дня} many{# дней} other{# дней}}',
                                        ['n' => $model['duration']],
                                        \Yii::$app->language
                                    );?>">
<div class="date d-flex flex-wrap flex-md-nowrap">
    <?php if($model['isOpenDate']){?>
        <div class="day"><?=$model['month'] ?></div>
    <?php }else{?>
        <div class="day"><?=$model['dayStart'] ?></div>
        <div class="month"><?=$model['monthStart'] ?></div>
        <div class="divide-box"><div class="divide"></div></div>
        <div class="d-flex flex-nowrap">
            <div class="day"><?=$model['dayEnd'] ?></div>
            <div class="month"><?=$model['monthEnd'] ?></div>
            <div class="year"><?=$model['year'] ?></div>
        </div>
    <?php }?>
</div>
<div class="price-box d-flex">
    <div class="price">
        <span class="new-price"><?=Yii::$app->currency->convert($model['price'])?></span>
    </div>
    <div class="currency">грн</div>
    <button class="reserve">
        <?php if ($model['spaces'] > 0):?>
            <?=Html::a(Yii::t('app', 'Book'), ['order/add', 'id' => $model['id']])?></span>
        <?php else: ?>
            <?=Yii::t('app', 'Booked')?>
        <?php endif; ?>
    </button>
</div>