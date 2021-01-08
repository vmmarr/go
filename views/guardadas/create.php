<?php

use yii\bootstrap4\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Guardadas */

$this->title = 'Create Guardadas';
$this->params['breadcrumbs'][] = ['label' => 'Guardadas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="guardadas-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
