<?php

use yii\grid\GridView;
use yii\helpers\Url;

$this->title = $title;

$url = Url::to([
    'site/index',
    'tripCorpId' => 3,
    'tripServiceId' => 2,
    'airPortName' => 'Домодедово, Москва'
]);
?>
<div class="site-index">

    <div class="body-content">

        <div class="row">
            <div class="col-lg-12 mb-12">
                <h2><?= $title ?></h2>
                <p><a href="<?= $url ?>"><strong>Показать записи</strong></a></p>
                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                ]); ?>
            </div>
        </div>

    </div>
</div>
