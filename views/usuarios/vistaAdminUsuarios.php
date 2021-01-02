<?php

use app\models\Usuarios;
use kartik\icons\Icon;
use yii\helpers\Html;
use yii\helpers\Url;

$archivo = $model->comprobarImagen($model->id . '.png');
?>

<div class="row d-flex justify-content-between horizontal">
    <div class="col-md-2 justify-content-center mt-3">
        <?php if ($archivo) : ?>
                <?=Html::img(Usuarios::enlace($model->id . '.png'))?>
            <?php  else : ?>
                <?=Html::img(Usuarios::enlace('perfil.png'))?>
        <?php endif; ?>
    </div>
    <div class="col-md-6 mt-5">
        <?=Html::a(Html::encode($model->username), ['usuarios/perfil', 'id' => $model->id])?>
    </div>
    <div class="col-md-4 mt-5 mb-3">
        <?=Html::a(Icon::show('trash', ['framework' => Icon::FA]) . 'Eliminar', ['delete', 'id' => $model->id], [
                            'class' => 'btn btn-danger',
                            'data' => [
                                'confirm' => '¿Eliminar usuario?',
                                'method' => 'post',
                            ],
                        ])?>
        <?=Html::a('Editar', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']);?>
    </div>
</div>