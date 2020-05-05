<?php
/* @var $this yii\web\View */

use kartik\icons\Icon;
use yii\bootstrap4\ActiveForm;
use yii\helpers\Html;

$this->registerCssFile('@web/css/indexUsuarios.css');
$this->registerJsFile('@web/js/indexUsuarios.js', [
    'depends' => [
        \yii\web\JqueryAsset::className()
    ]
]);
trim($cadena);
?>
<p>
    <?= Html::beginForm(['index'], 'get') ?>
        <div class="form-group">
            <?= Html::textInput('cadena', Html::encode($cadena), ['class' => 'form-control']) ?>
        </div>
        <div class="form-group">
            <!-- <?= Html::submitButton('Buscar', ['class' => 'btn btn-primary']) ?> -->
            <?= Html::submitButton(Icon::show('search', ['framework' => Icon::BSG]), ['class' => 'btn btn-primary']) ?>
        </div>
    <?= Html::endForm() ?>
</p>
<?php

$archivo;
if ($cadena == '') :
    $totalFilas = count($fila);
    
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
    <?php endfor;
elseif (count($query) > 0) :
    $totalFilas = count($query);
    
    for ($i = 0; $i < $totalFilas; $i++) :
        $archivo = $model->comprobarImagen($query[$i]['id'] . '.png');
        ?>
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div class="fotoNombre">
                        <?php
                        if ($archivo) : ?>
                            <?=Html::img(['download', 'fichero' => $query[$i]['id'] . '.png']);?>
                        <?php  else : ?>
                            <?=Html::img(['download', 'fichero' => 'perfil.png']);?>
                        <?php endif; ?>

                        <?=Html::a($query[$i]['username'])?>
                    </div>
                    <a href="#" class="btn btn-primary btn-sm">Seguir</a>
                </div>
            </div>
        </div>
    </div>
    <?php endfor;
else :?>
    <h3>No existe ningun usuario</h3>
<?php endif;?>
