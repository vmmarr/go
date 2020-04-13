<?php

use yii\bootstrap4\Html;
use yii\bootstrap4\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Publicaciones */
/* @var $form yii\bootstrap4\ActiveForm */
?>

<div class="publicaciones-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'usuario_id')->textInput(['value' => Yii::$app->user->id]) ?>


    <?= $form->field($model, 'created_at')->textInput() ?>

    <?= $form->field($model, 'update_at')->textInput() ?>
    <?= $form->field($model, 'descripcion')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'imagen')->fileInput() ?>

    
    <div class="form-group">
    <!-- <button>Publicar</button> -->
        <!-- <?=Html::a('Subir imagen', ['subida', 'id' => Yii::$app->user->id], ['class' => 'btn btn-outline-secondary']);?> -->
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
