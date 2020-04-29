<?php
/* @var $this yii\web\View */

use kartik\icons\Icon;
use yii\bootstrap4\ActiveForm;
use yii\helpers\Html;

$this->registerCssFile('@web/css/indexUsuarios.css');
?>

<?php $form = ActiveForm::begin([
        'action' => ['buscar'],
        'method' => 'get',
    ]); ?>
    <?= $form->field($model, 'nombre') ?>

    <!-- <div class="form-group"> -->
        <?= Html::submitButton(Icon::show('search', ['framework' => Icon::BSG]), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Icon::show('refresh', ['framework' => Icon::BSG]), ['class' => 'btn btn-outline-secondary']) ?>
    <!-- </div> -->
<?php ActiveForm::end();

    $totalFilas = count($fila);
    $archivo;

for ($i = 0; $i < $totalFilas; $i++) :
    $archivo = $model->comprobarImagen($fila[$i]['id'] . '.png');
    ?>
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div class="fotoNombre">
                        <?php
                        if ($archivo) : ?>
                            <?=Html::img(['download', 'fichero' => $fila[$i]['id'] . '.png']);?>
                        <?php  else : ?>
                            <?=Html::img(['download', 'fichero' => 'perfil.png']);?>
                        <?php endif; ?>

                        <?=Html::a($fila[$i]['username'])?>
                    </div>
                    <a href="#" class="btn btn-primary btn-sm">Seguir</a>
                </div>
            </div>
        </div>
    </div>

<?php endfor ?>
