<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
?>
 
<h1>Recover Password</h1>
<?php $form = ActiveForm::begin([
    'method' => 'post',
    'enableAjaxValidation' => true,
    'enableClientValidation' => true,
]);
?>
 
<div class="form-group">
    <?= $form->field($model, 'email')->textInput() ?>  
</div>
 
<?= Html::submitButton('Recover Password', ['class' => 'btn btn-primary']) ?>
 
<?php $form->end() ?>