<?php

use kartik\date\DatePicker;
use kartik\datecontrol\DateControl;
use kartik\icons\Icon;
use yii\bootstrap4\Html;
use yii\bootstrap4\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\PublicacionesSearch */
/* @var $form yii\bootstrap4\ActiveForm */
?>

<div class="publicaciones-search">
    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1
        ],
        'layout' => 'horizontal',
    ]); ?>
    <div class="row d-flex justify-content-center">
        <div class="col-8">
            <?= $form->field($model, 'buscar')->widget(DatePicker::classname(), [
                'options' => [],
                'pluginOptions' => [
                    'endDate' => '0d',
                    'autoclose'=>true,
                    'format' => 'yyyy-mm-dd'
                ]
            ]);
            ?>
            
        </div>
        <div class="col-3">
            <div class="form-group">
                <?= Html::submitButton(Icon::show('search', ['framework' => Icon::FA]), ['class' => 'btn btn-primary']) ?>
            </div>
        </div>
    </div>
    <?php ActiveForm::end(); ?>
</div>
