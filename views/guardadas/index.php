<?php

use kartik\icons\Icon;
use yii\bootstrap4\Html;
use yii\widgets\ListView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\LibrosSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '';
// $this->params['breadcrumbs'][] = $this->title;
$this->registerCssFile('@web/css/indexPublicaciones.css');
$this->registerJsFile('@web/js/indexPublicaciones.js', [
    'depends' => [
        \yii\web\JqueryAsset::className()
    ]
]);

$this->registerCssFile('@web/css/magnific-popup.css');
$this->registerJsFile('@web/js/jquery.magnific-popup.js', [
    'depends' => [
        \yii\web\JqueryAsset::className()
    ]
]);
\yii\web\YiiAsset::register($this);
?>
<div class="guardadas-index">
    <?= ListView::widget([
        'dataProvider' => $dataProvider,
        'itemView' => 'vistaGuardadas'
    ]) ?>

    <?=Html::a(Icon::show('arrow-up', ['framework' => Icon::FAS]), '#', ['class' => 'btn btn-primary up'])?>
</div
