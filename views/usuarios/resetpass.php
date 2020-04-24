<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
?>
 
<h1>Reset Password</h1>
<div>
    <?php $form = ActiveForm::begin([
        'method' => 'post',
        'enableClientValidation' => true,
    ]);
    ?>
        <?= $form->field($model, 'email')->textInput() ?>  

        <?= $form->field($model, 'password')->passwordInput() ?>  
        
        <?= $form->field($model, 'password_repeat')->passwordInput() ?>  

        <?= $form->field($model, 'verification_code')->textInput() ?>  

        <?= $form->field($model, 'recover')->hiddenInput()->label(false) ?>  
        
        <div class="form-group">
            <?= Html::submitButton('Reset password', ['class' => 'btn btn-primary']) ?>
        </div>
    <?php ActiveForm::end(); ?>
</div>