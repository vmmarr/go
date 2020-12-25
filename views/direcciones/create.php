<?php

use yii\bootstrap4\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Direcciones */

$this->title = 'Create Direcciones';
$this->params['breadcrumbs'][] = ['label' => 'Direcciones', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="direcciones-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
