<?php

use kartik\file\FileInput;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
?>

<?php $form = ActiveForm::begin() ?>

    <?= $form->field($model, 'imagen')->widget(FileInput::class, [
        'pluginOptions' => [
            'showPreview' => false,
            'showUpload' => false,
            'allowedFileExtensions' => ["jpg", "png"],
            'dropZoneEnabled' => false,
            'required' => true,
            'resizeImage' => true,
            'maxImageWidth' => 500,
            'maxImageHeight' => 500,
        ]
    ]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

<?php ActiveForm::end() ?>