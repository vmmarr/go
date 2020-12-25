<?php

use app\models\Direcciones;
use kartik\file\FileInput;
use yii\bootstrap4\Html;
use yii\bootstrap4\ActiveForm;
use yii\bootstrap4\Modal;
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
        <?= $form->field($model, 'direccion_id',
            [
              'template' => "{label}<div class='input-group'><div class=\"input-group-prepend\"></div> {auto} {input}</div>{error}",
              'parts' => [
                '{auto}' => \yii\jui\AutoComplete::widget([
                  'value' => $model->direccion_id,
                  'options' => ['class' => 'form-control', 'placeholder' => 'Introducir Direccion',
                  'id' => 'autocomplete'],
                  'clientOptions' => [
                    'source' => new \yii\web\JsExpression('function(request, response){
                        $.ajax({
                            type: "GET",
                            url: "' . \yii\helpers\Url::to(['direcciones/buscar']) . '", 
                            data: {"nombre": request.term },
                            success: function(data){response(data);
                            }
                        });
                        }'),
                    'minLength' => '1',
                    'select' => new \yii\web\JsExpression('function(event, ui){
                        if(ui.item.id == null){
                            $("#add-dir").modal("show")
                        } else {
                            $(event.target).val(ui.item.label);
                            $("#' . Html::getInputId($model, 'direccion_id') . '").val(ui.item.id);
                        }
                        return false;
                        }')
                  ]
                ])
              ]
            ])
            ->hiddenInput()
          ?>

        <div class="form-group">
            <?= Html::submitButton('Publicar', ['class' => 'btn btn-success']) ?> 
        </div>

    <?php ActiveForm::end(); ?>
</div>
<?php Modal::begin([
    'id' => 'add-dir'
]) ?>
<?php $form = ActiveForm::begin([
    'action' => ['/direcciones/create?resp=json'],
    'id' => 'form-add-dir',
    'enableAjaxValidation' => true
]);
$dir = new Direcciones()
?>
<?= $form->field($dir, 'nombre') ?>
<?= $form->field($dir, 'latitud') ?>
<?= $form->field($dir, 'longitud') ?>
<div class="text-right">
    <?= Html::submitButton('Guardar', ['class' =>'btn btn-success']) ?>
</div>
<?php ActiveForm::end() ?>
<?php Modal::end() ?>
<?php $this->registerJs('
$(document).on("beforeSubmit","form#form-add-dir", function(){
    let form = $(this);
    let data = form.serialize();
    if (form.find(".is-invalid").length) {
        return false;
    }
    let request = $.ajax({
        url: form.attr("action"),
        data: form.serialize(),
        type: form.attr("method"),
    });
    request.done(function(response){
        console.log(response);
        $("#autocomplete").val(response.nombre);
        $("#' . Html::getInputId($model, 'direccion_id') . '").val(response.id);
        $("#add-dir").modal("hide");
    });
    return false;
})
') ?>