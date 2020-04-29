<?php
/* @var $this yii\web\View */

use app\models\Usuarios;
use kartik\icons\Icon;
use yii\bootstrap4\ActiveForm;
use yii\helpers\Html;

$this->registerCssFile('@web/css/indexUsuarios.css');

$archivo = Usuarios::comprobarImagen($model->id . '.png');
?>

<div class="row">
    <div class="col">
        <div class="card">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div class="fotoNombre">
                    <?php
                    if ($archivo) : ?>
                        <?=Html::img(['download', 'fichero' => $model->id . '.png']);?>
                    <?php else : ?>
                        <?=Html::img(['usuarios/download', 'fichero' => 'perfil.png']);?>
                    <?php endif; ?>
                    <?=Html::a($model->username)?>
                </div>
                <a href="#" class="btn btn-primary btn-sm">Seguir</a>
            </div>
        </div>
    </div>
</div>