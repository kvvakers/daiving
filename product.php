<?php
use yii\helpers\Html;
use yii\helpers\Url;
use common\components\EntityTypes;
use yii\widgets\ActiveForm;
use yii\widgets\ListView;
use yii\widgets\Pjax;

$this->params['breadcrumbs'][] = [
    'label' => Yii::t('app', 'Dive-resort'),
    'url' => ['/'],
    'class' => 'breadcrumbs__link'
];
$this->params['breadcrumbs'][] = [
    'label' => $model->entity->country->name,
    'url' => ['/country/show', 'url' => $model->entity->country->url],
    'class' => 'breadcrumbs__link'
];
$this->params['breadcrumbs'][] = [
    'label' => strip_tags($model->name),
    'class' => 'breadcrumbs__link'
];
$this->title = $model->name;
$this->registerLinkTag(['rel' => 'canonical', 'href' => Url::to(['entity/show', 'country' => $model->entity->country->url, 'url' => $model->seoUrl], true)]);

$this->registerJsFile("https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js");
$this->registerCssFile("https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css");
$this->registerCssFile("/css/product.css");
?>

<div class="header_bg">
    <div class="container">
        <h1 class="title"><?=$model->name?></h1>
        <div class="info-box">
            <div class="row  justify-content-start justify-content-lg-between">
                <?php if ($model->groupedTours['duration'] || $model->minDive):?>
                    <div class="mb-3 col-6 col-lg-4 col-xl-2">
                        <div class="duration">
                            <div class="info-title">Длительность</div>
                            <div class="info-descr">
                                <?php if ($model->groupedTours['duration']):?>
                                    <?=Yii::$app->i18n->messageFormatter->format(
                                        '{n, plural, one{# день} few{# дня} many{# дней} other{# дней}}',
                                        ['n' => $model->groupedTours['duration']],
                                        \Yii::$app->language
                                    );?>
                                <?php endif;?>
                                ~<?=Yii::$app->i18n->messageFormatter->format(
                                    '{n, plural, one{# погружение} few{# погружения} many{# погружений} other{# погружений}}',
                                    ['n' => $model->minDive],
                                    \Yii::$app->language
                                );?></div>
                        </div>
                    </div>
                <?php endif;?>
                <div class="mb-3 col-6 col-lg-4 col-xl-2">
                    <div class="yacht">
                        <div class="info-title">Яхта</div>
                        <div class="info-descr"><?=$model->entity->name?></div>
                    </div>
                </div>
                <div class="mb-3 col-6 col-lg-4 col-xl-2">
                    <div class="departures">
                        <div class="info-title">Порт отправления</div>
                        <div class="info-descr"><?=$model->departurePort?></div>
                    </div>
                </div>
                <div class="mb-3 col-6 col-lg-4 col-xl-2">
                    <div class="arrivals">
                        <div class="info-title">Порт прибытия</div>
                        <div class="info-descr"><?=$model->arrivalPort?></div>
                    </div>
                </div>
                <?php if ($model->countDive || $model->minCertificate):?>
                    <div class="mb-3 col-6 col-lg-4 col-xl-2">
                        <div class="requirements">
                            <div class="info-title">Требования</div>
                            <div class="info-descr">
                                <?php if ($model->minCertificate):?>
                                    <p>
                                        <?=Yii::t('app','Certification level') . ' ' .
                                        \common\components\Sertificate::getName($model->minCertificate)?>
                                    </p>
                                <?php endif;?>
                                <?php if ($model->countDive):?>
                                    <p>
                                        <?=Yii::t('app','At least') . ' ' . $model->countDive . ' '  . Yii::t('app','dives in the logbook')?>
                                    </p>
                                <?php endif;?>
                            </div>
                        </div>
                    </div>
                <?php endif;?>
            </div>
        </div>
    </div>
</div>
<?php if ($model->showTours):?>
    <?php if ($model->groupedTours['main']):?>
        <div class="container">
            <div class="date_tour">
                <div class="title"><?=Yii::t('app', 'Tour dates')?></div>

                <?php Pjax::begin([
                    'enablePushState' => false, // to disable push state
                    'enableReplaceState' => false // to disable replace state
                ]);?>
                <?= ListView::widget([
                    'dataProvider' => $model->toursData,
                    'itemView' => '_listToursProduct',
                    'emptyText' => '',
                    'itemOptions' => ['class' => 'date-box d-flex justify-content-between'],
                    'layout' => '{items}{pager}',
                    'pager' => [
                        'class' => \frontend\components\LinkPager::className()
                    ]
                ]);?>
                <?php Pjax::end();?>
            </div>
        </div>
    <?php endif;?>
<?php endif;?>
<div class="container">
    <div class="what-awaits">
        <div class="title"><?=Yii::t('app', 'What awaits us')?></div>
        <?php if($model->features):?>
            <div class="slider d-flex justify-content-between">
                <?php foreach ($model->features as $tag): ?>
                    <div class="d-flex align-items-center flex-column">
                        <img src="/images/slider_awaits<?=$tag->id?>.png" alt="">
                        <p class="text-center"><?=$tag->name?></p>
                    </div>
                <?php endforeach;?>
            </div>
        <?php endif;?>
        <?php $images = $model->images;?>
        <div class="galery container">
            <div class="row">
                <div class="col-12 col-lg-8 ">
                    <div>
                        <a href="<?=$model->getThumbUploadUrl('logo', 'w850')?>" class="fancy-gallery" data-fancybox-group="product_gallery" title="<?=$model->name?>">
                            <img src="<?=$model->getThumbUploadUrl('logo', 'w1200')?>" alt="<?=$model->name?>" title="<?=$model->name?>">
                        </a>
                    </div>
                    <?php if(count($images) > 2){?>
                        <div class="position-relative d-block d-lg-none">
                            <div class="see_all position-absolute" onclick="$('.galery .fancy-gallery').eq(0).click()">
                                <span>Cмотреть <?=count($images)?> фото</span>
                            </div>
                        </div>
                    <?php }?>
                </div>
                <?php if (count($images) > 0) { ?>
                    <div class="col-4 d-none d-lg-block">
                        <div>
                            <?php foreach ($images as $k => $image) { ?>
                                <?php if ($k == 0) { ?>
                                    <div class="mb-3">
                                        <a href="<?= $image->getUrl('original') ?>" class="fancy-gallery"
                                           data-fancybox-group="product_gallery" title="<?= $image->name ?: $model->name ?>">
                                            <img src="<?= $image->getUrl('h300') ?>" alt="<?= $image->name ?: $model->name ?>"
                                                 title="<?= $image->name ?: $model->name ?>">
                                        </a>
                                    </div>
                                <?php } elseif ($k == 1) { ?>
                                    <div class="position-relative">
                                        <a href="<?= $image->getUrl('original') ?>" class="fancy-gallery"
                                           data-fancybox-group="product_gallery" title="<?= $image->name ?: $model->name ?>">
                                            <img src="<?= $image->getUrl('h300') ?>" alt="<?= $image->name ?: $model->name ?>"
                                                 title="<?= $image->name ?: $model->name ?>">
                                        </a>
                                    </div>
                                <?php } else { ?>
                                    <a href="<?= $image->getUrl('original') ?>" class="fancy-gallery"
                                       data-fancybox-group="product_gallery" title="<?= $image->name ?: $model->name ?>"
                                       style="display: none;">
                                    </a>
                                <?php } ?>
                            <?php } ?>
                            <?php if(count($images) > 2){?>
                                <div class="see_all position-absolute" onclick="$('.galery .fancy-gallery').eq(0).click()">
                                    <span>Cмотреть <?=count($images)?> фото</span>
                                </div>
                            <?php }?>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
        <?php if($model->descr):?>
            <div class="text"><?=$model->descr?></div>
        <?php endif;?>
    </div>
    <?php if($model->sites):?>
        <div class="dive-saits">
            <div class="title"><?=Yii::t('app', 'Dive-site along the route')?></div>
            <div class="d-flex justify-content-between image-container">
                <?php foreach ($model->sites as $k => $site): ?>
                    <div class="image-box <?=$k==0?'':'d-none'?>">
                        <a href="<?=Url::toRoute(['sights/show', 'country' => $site->country->url, 'url' => $site->url])?>">
                            <?=Html::img($site->getThumbUploadUrl('logo', 'h300'), ['alt' => $site->name,'title' => $site->name])?>
                        </a>
                        <div class="text"><?=$site->name?></div>
                    </div>
                <?php endforeach;?>
            </div>
        </div>
    <?php endif;?>
    <?php if(!empty($model->days)):?>
        <div class="program">
            <div class="title"><?=Yii::t('app', 'Tour program')?></div>
            <?php foreach ($model->days as $k => $day):?>
                <div class="program-day-box">
                    <div class="d-flex justify-content-between mb-md-4">
                        <div class="day"><?=$day->name?></div>
                        <div class="develop minus plus">&mdash;</div>
                    </div>
                    <div class="day-content">
                        <div class="text mb-4">
                            <?=$day->description?>
                        </div>
                        <?php if($day->images):?>
                            <div class="container">
                                <div class="row mb-5">
                                    <?php foreach ($day->images as $k => $image):?>
                                        <div class="<?=$k==0?'col-12 col-md-6':'col-6 d-none d-md-block'?>">
                                            <a href="<?=$image->getUrl('original')?>" class="fancy-gallery w-100 program-day-img"
                                               data-fancybox-group="day<?=$day->id?>_gallery"
                                               title="<?=$image->name?:$day->name?>">
                                                <?=Html::img($image->getUrl('h300'),
                                                    ['alt' => $image->name?:$day->name, 'title' => $image->name?:$day->name]);?>
                                            </a>
                                        </div>
                                    <?php endforeach;?>
                                </div>
                            </div>
                        <?php endif;?>
                    </div>
                </div>
            <?php endforeach;?>
        </div>
    <?php endif;?>
    <div class="details">
        <div class="title"><?=Yii::t('app', 'Сost details')?></div>
        <div class="row">
            <div class="col-lg-6 col-xl-4">
                <div class="details-box">
                    <div class="subtitle">Включено</div>
                    <?=$model->include?>
                </div>
            </div>
            <div class="col-lg-6 col-xl-4">
                <div class="details-box">
                    <div class="subtitle">Не включено</div>
                    <?=$model->notInclude?>
                </div>
            </div>
            <div class="col-lg-6 col-xl-4">
                <div class="details-box">
                    <div class="subtitle">Дополнительные расходы</div>
                    <?=$model->additionalCost?>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="footer-info">
    <?php if ($model->entity->groupedTours['main']):?>
        <div class="container">
            <div class="row">
                <div class="col-12 col-xl-5">
                    <div class="pe-5">
                        <div class="title"><?=$model->name?></div>
                        <div class="container">
                            <ul class="row">
                                <li class="col-md-6 col-lg-4 col-xl-12"><?=$model->entity->name?></li>
                                <li class="col-md-6 col-lg-4 col-xl-12">
                                    <?php if ($model->groupedTours['duration']):?>
                                        <?=Yii::$app->i18n->messageFormatter->format(
                                            '{n, plural, one{# день} few{# дня} many{# дней} other{# дней}}',
                                            ['n' => $model->groupedTours['duration']],
                                            \Yii::$app->language
                                        );?>,
                                    <?php endif;?>
                                    <?php if ($model->minDive):?>
                                        ~<?=Yii::$app->i18n->messageFormatter->format(
                                            '{n, plural, one{# погружение} few{# погружения} many{# погружений} other{# погружений}}',
                                            ['n' => $model->minDive],
                                            \Yii::$app->language
                                        );?>
                                    <?php endif;?>
                                </li>
                                <?php
                                if($model->benefits){
                                    $benefits = explode("\n", $model->benefits);
                                    foreach ($benefits as $item){?>
                                        <li class="col-md-6 col-lg-4 col-xl-12"><?=$item?></li>
                                    <?php }
                                }
                                ?>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-xl-7">
                    <div class="date_tour">
                        <div class="title"><?=Yii::t('app', 'Tour dates')?></div>

                        <?php Pjax::begin([
                            'enablePushState' => false, // to disable push state
                            'enableReplaceState' => false // to disable replace state
                        ]);?>
                        <?= ListView::widget([
                            'dataProvider' => $model->entity->toursData,
                            'itemView' => '_listToursEntity',
                            'emptyText' => '',
                            'itemOptions' => ['class' => 'date-box d-flex justify-content-between'],
                            'layout' => '{items}{pager}',
                            'pager' => [
                                'class' => \frontend\components\LinkPager::className()
                            ]
                        ]);?>
                        <?php Pjax::end();?>
                    </div>
                </div>
            </div>
        </div>
    <?php endif;?>
</div>



<main id="main" style="background-color: #fff!important; color: #000!important;">
    <?php if ($model->getByTags()):?>
        <div class="links-box">
            <div class="center-page">
                <div class="col-left">
                    <strong class="section-title">Туры с похожим маршрутом на других яхтах</strong>
                    <ul class="links-box__list">
                        <?php foreach ($model->getByTags() as $item):?>
                            <li class="links-box__item">
                                <?=Html::a(strip_tags($item['name'].' - '.$item['productName']),
                                    ['entity/show', 'country' => $item['url'], 'url' => $item['seoUrl']],
                                    ['class' => 'links-box__link'])?>
                            </li>
                        <?php endforeach?>
                    </ul>
                </div>
            </div>
        </div>
    <?php endif;?>
    <div class="subscription-box">
        <div class="center-page">
            <div class="col">
                <strong class="section-title" style="color: #000;"><?=Yii::t('app', 'Don\'t waste your time to find boats')?><br /><?=Yii::t('app', 'We will send them ourselves')?></strong>
                <form action="#" class="subscription-form">
                    <div class="row">
                        <input type="email" name="email" placeholder="<?=Yii::t('app', 'Your email')?>" />
                        <input type="submit" class="submit" value="<?=Yii::t('app', 'Subscribe')?>"/>
                    </div>
                </form>
            </div>
            <div class="col share-box">
                <div class="h2" style="color: #000;">Присоединяйтесь к нам в соцсетях <br /> &nbsp;</div>
                <div class="social-icons">
                    <a href="https://www.facebook.com/safarimaris.ua" target="_blank"><img src="/facebook.png" alt="Facebook"></a>
                    <a href="https://t.me/safarimaris" target="_blank"><img src="/telegram.png" alt="Telegram"></a>
                </div>
            </div>
        </div>
    </div>
</main>

<div class="popup-holder">

    <div class="lightbox-holder booking">
        <div class="h1"><?=Yii::t('app', 'Order request');?></div>
        <?php $form = ActiveForm::begin([
            'action' => ['order/add'],
            'options' => ['class' => 'booking-form']
        ]); ?>
        <input type="hidden" id="tourId" name="tourId"/>
        <input type="hidden" id="entityId" name="entityId" value="<?=$model->entity->id;?>"/>
        <div class="row">
            <span class="form-title"><?=Yii::t('app', 'Tour');?></span>
            <div class="tour-selected">
            </div>
        </div>
        <div class="row">
            <label for="user"><?=Yii::t('app', 'First name, last name');?></label>
            <input type="text" id="user" name="user"/>
        </div>
        <div class="row">
            <label for="email"><?=Yii::t('app', 'Email');?></label>
            <input type="email" id="email" name="email"/>
        </div>
        <div class="row">
            <label for="phone"><?=Yii::t('app', 'Phone');?></label>
            <input type="tel" id="phone" name="phone"/>
        </div>
        <div class="row">
            <label for="cntPeople"><?=Yii::t('app', 'Persons count');?></label>
            <input type="number" id="cntPeople" name="cntPeople"/>
        </div>
        <div class="row">
            <label for="comments"><?=Yii::t('app', 'Comments');?></label>
            <textarea id="comments" cols="30" rows="10" name="comments"></textarea>
        </div>
        <div class="row">
            <div class="btn-holder">
                <a href="#" class="btn"><?=Yii::t('app', 'Send request');?></a>
            </div>
            <ul class="check-list">
                <li><?=Yii::t('app', 'Best price guarantee');?></li>
                <li><?=Yii::t('app', 'No bookings fee');?></li>
            </ul>
        </div>
        <?php ActiveForm::end(); ?>
    </div>

    <?php if ($tourModel && $tourModel->product):?>
        <?= $this->render('@app/views/rest/product', ['tour' => $tourModel]) ?>
    <?php endif;?>

</div>
