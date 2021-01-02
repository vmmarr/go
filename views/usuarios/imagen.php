<?php

use kartik\file\FileInput;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
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
    <?= $form->field($model, 'extension')->textInput()->hiddenInput(['id' => 'ruta'])->label(false) ?>

    <div class="form-group">
        <?= Html::submitButton('Guardar', ['class' => 'btn btn-success']) ?>
    </div>

<?php ActiveForm::end() ?>