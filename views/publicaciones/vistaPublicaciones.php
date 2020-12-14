<?php

use app\models\Likes;
use app\models\Publicaciones;
use app\models\Usuarios;
use kartik\icons\Icon;
use yii\helpers\Html;
use yii\helpers\Url;

$url = Url::to(['likes/likes', 'usuario_id' => $model->usuario_id, 'publicacion_id' => $model->id]);
$js = <<<EOT
    $(document).ready(function () {
        $.ajax({
            type: 'GET',
            url: '$url',
            success: function (data) {
                data = JSON.parse(data);
                $('#like' + '$model->id').removeClass('far');
                $('#like' + '$model->id').addClass(data.class);
                $('#numLikes' + '$model->id').text(data.contador);
            }
        });
        $('#like' + '$model->id').click(function (e) {
            e.preventDefault();
            $.ajax({
                type: 'POST',
                url: '$url',
                success: function (data) {
                    data = JSON.parse(data);
                    $('#like' + '$model->id').removeClass('fas');
                    $('#like' + '$model->id').addClass(data.class);
                    $('#numLikes' + '$model->id').text(data.contador);
                }
            })
        });
    });
EOT;

$this->registerJs($js);

$js = <<<EOT
$(document).ready(function() {
    $('.image').magnificPopup({
        type:'image'
    });
  });
EOT;

$this->registerJs($js);
?>

<div class="row d-flex justify-content-center align-items-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div class="fotoNombre">
                    <?php
                    $archivo = $model->usuario->comprobarImagen($model->usuario->imagenUrl);
                    if ($archivo) : ?>
                        <?=Html::img(Publicaciones::enlace($model->usuario->imagenUrl))?>
                    <?php  else : ?>
                        <?=Html::img(Publicaciones::enlace('perfil.png'))?>
                    <?php endif; ?>
                            
                    <?=Html::a($model->usuario->username)?>
                </div>
                <div class="prueba">
                    <?=Yii::$app->formatter->asRelativeTime($model->created_at)?>
                    <button class="btn dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <?=Icon::show('ellipsis-v', ['framework' => Icon::FA])?>
                    </button>
                    <div class="dropdown-menu">
                        <?=Html::a(Icon::show('edit', ['framework' => Icon::FA]) . 'Modificar', ['update', 'id' => $model->id], ['class' => 'dropdown-item']);?>
                        <?=Html::a(Icon::show('trash', ['framework' => Icon::FA]) . 'Borrar', ['delete', 'id' => $model->id], [
                            'class' => 'dropdown-item',
                            'data' => [
                                'confirm' => '¿Eliminar publicacion?',
                                'method' => 'post',
                            ],
                        ])?>
                    </div> 
                </div>
            </div>
            <div class="col-12 d-flex justify-content-center align-items-center">
                <div>
                <?php 
                if ($model->extension !== 'mp4') { ?>
                    <?=Html::a(Html::img(Publicaciones::enlace($model->imagenUrl), ['class' => 'tamano']), Publicaciones::enlace($model->imagenUrl), ['class' => 'image col-md-12'])?>
                <?php } else { ?>
                    <video class="tamano" controls>
                        <source src="<?=Publicaciones::enlace($model->imagenUrl)?>" type="video/mp4">
                    </video> 
                <?php } ?>
                </div>
            </div>
            <div class="ml-4 mt-1">
                <?=Icon::show('comment', ['framework' => Icon::FAR])?>
                <?=Html::tag('span', $model->totalComentarios); ?>
                <?=Html::a(null, null, [
                    'id' => 'like' . $model->id,
                    'class' => 'text-danger fa-heart',
                    'data-pjax' => 0
                ])?>
                <?=Html::tag('span', '', ['id' => 'numLikes' . $model->id]); ?>
            </div>
            <?php 
            if ($model->descripcion != '') : ?>
                <div class="card-body d-flex justify-content-between align-items-center comentario">
                    <?=Html::tag('p', Html::a($model->usuario->username, ['usuarios/perfil', 'id' => $model->usuario->id]) . ' ' . $model->descripcion)?>
                </div>
            <?php endif; ?>
            <div class="comentarios">
                <!-- Muestra los 2 ultimos comentarios -->
                <?php
                $filas = $model->comentarios;?>
            </div>
            <div class="d-flex justify-content-end align-items-center mb-4 mr-5">
                <?=Html::a('Nuevo comentario', ['comentarios/create', 'id' => $model->id], ['class' => 'btn btn-success']); ?>
            </div>
        </div>
    </div>
</div>