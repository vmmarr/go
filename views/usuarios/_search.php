<?php

use kartik\icons\Icon;
use yii\bootstrap4\Html;
use yii\bootstrap4\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\UsuariosSearch */
/* @var $form yii\bootstrap4\ActiveForm */
?>

<div class="usuarios-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1
        ],
        'layout' => 'horizontal',
    ]); ?>

    <div class="row">
        <div class="col-md-10">
            <?= $form->field($model, 'buscar') ?>
        </div>
            
            <div class="col-md-2">
                <div class="form-group">
                    <?= Html::submitButton(Icon::show('search', ['framework' => Icon::FA]), ['class' => 'btn btn-primary']) ?>
                </div>
            </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
