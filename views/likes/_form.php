<?php

use yii\bootstrap4\Html;
use yii\bootstrap4\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Likes */
/* @var $form yii\bootstrap4\ActiveForm */
?>

<div class="likes-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'usuario_id')->textInput()->hiddenInput(['value' => Yii::$app->user->id])->label(false); ?>

    <?= $form->field($model, 'publicacion_id')->textInput()->hiddenInput(['value' => $publi->id])->label(false)?>

    <div class="form-group">
        <?= Html::submitButton('Guardar', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
