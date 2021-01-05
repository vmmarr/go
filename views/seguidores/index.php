<?php
/* @var $this yii\web\View */

use yii\widgets\ListView;
use yii\widgets\Pjax;

$this->title = '';
// $this->params['breadcrumbs'][] = $this->title;

$this->registerCssFile('@web/css/indexUsuarios.css');
$this->registerJsFile('@web/js/indexUsuarios.js', [
    'depends' => [
        \yii\web\JqueryAsset::className()
    ]
]);
?>
<div class="seguidores-index">
<?php Pjax::begin([
    'timeout' => 500000,
]); ?>
    <?php //echo $this->render('_search', ['model' => $searchModel]); ?>
    <?= ListView::widget([
        'dataProvider' => $dataProvider,        
        'itemView' => 'vistaUsuarios',
    ]) ?>
<?php Pjax::end(); ?>
    </div>