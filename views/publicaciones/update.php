<?php

use yii\bootstrap4\ActiveForm;
use yii\bootstrap4\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Publicaciones */

$this->title = 'Update Publicaciones: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Publicaciones', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="publicaciones-update">
    <?php
    $form = ActiveForm::begin(); ?>
        <?= $form->field($model, 'usuario_id')->textInput()->hiddenInput(['value' => Yii::$app->user->id])->label(false); ?>
        <?= $form->field($model, 'created_at')->textInput()->hiddenInput(['value' => Yii::$app->formatter->asDatetime(time(), 'php:d-m-Y H:i:s')])->label(false) ?>
        <?= $form->field($model, 'descripcion')->textarea(['maxlength' => true]) ?>
        <div class="form-group">
            <?= Html::submitButton('Publicar', ['class' => 'btn btn-success']) ?> 
        </div>

    <?php ActiveForm::end(); ?>

</div>
