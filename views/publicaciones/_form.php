<?php

use yii\bootstrap4\Html;
use yii\bootstrap4\ActiveForm;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\Publicaciones */
/* @var $form yii\bootstrap4\ActiveForm */
?>
<div class="publicaciones-form">
    <?php
    $form = ActiveForm::begin(); ?>
        
        <?= $form->field($model, 'imagen')->fileInput() ?>
        <?= $form->field($model, 'usuario_id')->textInput()->hiddenInput(['value' => Yii::$app->user->id])->label(false); ?>
        <?= $form->field($model, 'created_at')->textInput()->hiddenInput(['value' => Yii::$app->formatter->asDatetime(time(), 'php:d-m-Y H:i:s')])->label(false) ?>
        <?= $form->field($model, 'descripcion')->textarea(['maxlength' => true]) ?>
        <div class="form-group">
            <?= Html::submitButton('Publicar', ['class' => 'btn btn-success']) ?> 
        </div>

    <?php ActiveForm::end(); ?>
</div>
