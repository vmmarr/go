<?php

use kartik\password\PasswordInput;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
?>
 
<h1>Reset Password</h1>
<div>
    <?php $form = ActiveForm::begin([
        'method' => 'post',
        'enableAjaxValidation' => true,
        'enableClientValidation' => true,
    ]);
    ?>
        <?= $form->field($model, 'email')->textInput() ?>  

        <?= $form->field($model, 'password')->widget(PasswordInput::class, [
            'pluginOptions' => ['showMeter' => false]
        ])?>
        
        <?= $form->field($model, 'password_repeat')->widget(PasswordInput::class, [
            'pluginOptions' => ['showMeter' => false]
        ])?>

        <?= $form->field($model, 'verification_code')->textInput() ?>  

        <?= $form->field($model, 'recover')->hiddenInput()->label(false) ?>  
        
        <div class="form-group">
            <?= Html::submitButton('Reset password', ['class' => 'btn btn-primary']) ?>
        </div>
    <?php ActiveForm::end(); ?>
</div>