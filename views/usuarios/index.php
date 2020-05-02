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
?>
<p>
    <?= Html::beginForm(['index'], 'get') ?>
        <div class="form-group">
            <?= Html::textInput('cadena', $cadena, ['class' => 'form-control']) ?>
        </div>
        <div class="form-group">
            <?= Html::submitButton('Buscar', ['class' => 'btn btn-primary']) ?>
        </div>
    <?= Html::endForm() ?>
</p>

<?php
$totalFilas = count($fila);
$archivo;
var_dump($busqueda->totalCount);
if ($busqueda->totalCount > 0) :
    for ($i = 0; $i < $busqueda->totalCount; $i++) :
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
<?php endif;

