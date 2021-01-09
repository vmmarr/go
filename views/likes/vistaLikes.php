<?php

use app\models\Publicaciones;
use app\models\Usuarios;
use yii\bootstrap4\Html;
use yii\helpers\Url;

$url = Url::to(['seguidores/seguir', 'usuario_id' => Yii::$app->user->identity->id, 'seguidor_id' => $model->usuario_id]);
$jsSeguir = <<<EOT
    $(document).ready(function () {
        $.ajax({
            type: 'GET',
            url: '$url',
            success: function (data) {
                data = JSON.parse(data);
                $('#btnSeguir' + '$model->usuario_id').removeClass('btn-primary');
                $('#btnSeguir' + '$model->usuario_id').removeClass('btn-outline-dark');
                $('#btnSeguir' + '$model->usuario_id').addClass(data.class);
                $('#btnSeguir' + '$model->usuario_id').text(data.text);
            }
        });
        $('#btnSeguir' + '$model->usuario_id').click(function (e) {
            e.preventDefault();
            $.ajax({
                type: 'POST',
                url: '$url',
                success: function (data) {
                    data = JSON.parse(data);
                    $('#btnSeguir' + '$model->usuario_id').removeClass('btn-outline-dark');
                    $('#btnSeguir' + '$model->usuario_id').removeClass('btn-primary');
                    $('#btnSeguir' + '$model->usuario_id').addClass(data.class);
                    $('#btnSeguir' + '$model->usuario_id').text(data.text);
                }
            })
        });
    });
EOT;

$this->registerJs($jsSeguir);
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
            <?=Html::a($model->usuario->username, ['usuarios/perfil', 'id' => $model->usuario_id])?>
        </div>
    </div>
    <div class="col-5 mt-4">
        <?php if (!Usuarios::estaBloqueado($model->usuario_id)) : 
            if ($model->usuario->username !== Yii::$app->user->identity->username) : ?>
                <?=Html::a('Seguir', null, [
                    'id' => 'btnSeguir' . $model->usuario_id,
                    'class' => 'btn btn-primary',
                    'data-pjax' => 0
                ])?>      
            <?php endif ?>
        <?php endif ?>
    </div>
</div>