<?php
/* @var $this yii\web\View */

use kartik\icons\Icon;
use yii\helpers\Html;
use yii\widgets\ListView;
use yii\widgets\Pjax;

$this->title = 'Usuarios';
// $this->params['breadcrumbs'][] = $this->title;

$this->registerCssFile('@web/css/indexUsuarios.css');
$this->registerJsFile('@web/js/indexUsuarios.js', [
    'depends' => [
        \yii\web\JqueryAsset::className()
    ]
]);
?>
<div class="usuarios-index">
<?php Pjax::begin([
    'timeout' => 500000,
]); ?>
    <?php echo $this->render('_search', ['model' => $searchModel]); ?>
    <?= ListView::widget([
        'dataProvider' => $dataProvider,        
        'itemView' => 'vistaAdminUsuarios',
    ]) ?>
<?php Pjax::end(); ?>
</div
