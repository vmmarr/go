<?php

use kartik\file\FileInput;
use yii\bootstrap4\Html;
use yii\bootstrap4\ActiveForm;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\Publicaciones */
/* @var $form yii\bootstrap4\ActiveForm */
$js = <<<EOT
    $(document).ready(function() {
        $('.file-caption-main').change(function() {
            var value = $('.file-caption-name').attr('title');
            var posicion = value.lastIndexOf('.');
            $('#ruta').val(value.substr(posicion+1,value.length));
        });
    });
EOT;

$this->registerJs($js);
?>
<div class="publicaciones-form">
    <?php
    $form = ActiveForm::begin(); ?>
        <?= $form->field($model, 'imagen')->widget(FileInput::class, [
            'pluginOptions' => [
                'showPreview' => false,
                'showUpload' => false,
                'allowedFileExtensions' => ["jpg", "png", "mp4"],
                'dropZoneEnabled' => false,
                'required' => true,
                'resizeImage' => true,
                'maxImageWidth' => 500,
                'maxImageHeight' => 500,
            ]
        ]) ?>
        <?= $form->field($model, 'extension')->textInput()->hiddenInput(['id' => 'ruta'])->label(false) ?>
        <?= $form->field($model, 'usuario_id')->textInput()->hiddenInput(['value' => Yii::$app->user->id])->label(false); ?>

        <?= $form->field($model, 'descripcion')->textarea(['maxlength' => true]) ?>
        <div class="form-group">
            <?= Html::submitButton('Publicar', ['class' => 'btn btn-success']) ?> 
        </div>

    <?php ActiveForm::end(); ?>
</div>
