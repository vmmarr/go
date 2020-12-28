<?php

use app\models\Publicaciones;
use yii\bootstrap4\Html;
?>
<div class="row d-flex justify-content-between horizontal">
    <div class="col justify-content-center mt-3 ml-2">
        <div class="fotoNombre">
            <?php
            $archivo = $model->usuario->comprobarImagen($model->usuario->imagenUrl);
            if ($archivo) : ?>
                <?=Html::img(Publicaciones::enlace($model->usuario->imagenUrl))?>
            <?php  else : ?>
                <?=Html::img(Publicaciones::enlace('perfil.png'))?>
            <?php endif; ?>
            <?=Html::a($model->usuario->username, ['usuarios/perfil', 'id' => $model->usuario_id]) . ' '. $model->comentario?>
        </div>
    </div>
</div>