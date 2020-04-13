<?php

use yii\bootstrap4\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\LibrosSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="publicaciones-index">

    <p>
        <?= Html::a('Publicar', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            'usuario_id',
            'descripcion',
            'update_at:datetime',
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view} {update} {delete}',
            ],
            'imagen',
        ],
    ]); ?>
</div>