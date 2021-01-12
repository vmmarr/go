<?php

use app\models\Likes;
use app\models\Publicaciones;
use app\models\Usuarios;
use kartik\icons\Icon;
use yii\bootstrap4\Modal;
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

$urlG = Url::to(['guardadas/create', 'publicacion_id' => $model->id]);
$js = <<<EOT
    $(document).ready(function () {
        $.ajax({
            type: 'GET',
            url: '$urlG',
            success: function (data) {
                data = JSON.parse(data);
                $('#guardado' + '$model->id').removeClass('far');
                $('#guardado' + '$model->id').addClass(data.class);
            }
        });
        $('#guardado' + '$model->id').click(function (e) {
            e.preventDefault();
            $.ajax({
                type: 'POST',
                url: '$urlG',
                success: function (data) {
                    data = JSON.parse(data);
                    $('#guardado' + '$model->id').removeClass('fas');
                    $('#guardado' + '$model->id').addClass(data.class);
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

    $('.btn-ajax-modal').click(function (){
        var elm = $(this),
            target = elm.attr('data-target'),
            ajax_body = elm.attr('value');
 
        $(target).modal('show')
            .find('.modal-content')
            .load(ajax_body);
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

                    <?=Html::a($model->usuario->username, ['usuarios/perfil', 'id' => $model->usuario_id])?>
                    <?php 
                        if ($model->direccion !== null) : ?>
                            <div class="ml-5 mt-0">
                                <?=Html::a($model->direccion->nombre, ['direcciones/view', 'id' => $model->direccion_id])?>
                            </div>
                    <?php endif ?>
                </div>
                <div class="prueba">
                    <?=Yii::$app->formatter->asRelativeTime($model->created_at)?>
                    <?php if (Yii::$app->user->identity->username === $model->usuario->username || Usuarios::isAdmin()) : ?>
                        <button class="btn dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <?=Icon::show('ellipsis-v', ['framework' => Icon::FA])?>
                        </button>
                        <div class="dropdown-menu">
                            <?=Html::a(Icon::show('edit', ['framework' => Icon::FA]) . 'Modificar', ['publicaciones/update', 'id' => $model->id], ['class' => 'dropdown-item']);?>
                            <?=Html::a(Icon::show('trash', ['framework' => Icon::FA]) . 'Borrar', ['publicaciones/delete', 'id' => $model->id], [
                                'class' => 'dropdown-item',
                                'data' => [
                                    'confirm' => '¿Eliminar publicacion?',
                                    'method' => 'post',
                                ],
                                ])?>
                        </div> 
                    <?php endif ?>
                </div>
            </div>
            <div class="col-12 d-flex justify-content-center align-items-center">
                <div>
                <?php 
                if ($model->extension !== 'mp4') { ?>
                    <?=Html::a(Html::img(Publicaciones::enlace($model->imagenUrl), ['class' => 'tamano']), Publicaciones::enlace($model->imagenUrl), ['class' => 'image'])?>
                <?php } else { ?>
                    <video class="tamano" controls>
                        <source src="<?=Publicaciones::enlace($model->imagenUrl)?>" type="video/mp4">
                    </video> 
                <?php } ?>
                </div>
            </div>
            <div class="ml-4 mt-1">
                <?php
                if (count($model->getComentarios()->all()) == 0) : ?> 
                    <?=Icon::show('comment', ['framework' => Icon::FAR])?>
                <?php else : ?>
                    <?= Html::button(Icon::show('comment', ['framework' => Icon::FAR]), [
                    'class' => 'btn-ajax-modal enlace',
                    'value' => Url::to(['comentarios/index', 'id' => $model->id]),
                    'data-target' => '#modal_comentarios',
                ]);

                Modal::begin([
                    'id' => 'modal_comentarios',
                ]);
                echo '<div class="modal-content"></div>';
                Modal::end();
                ?>
                <?php endif; ?>

                <?=Html::tag('span', $model->totalComentarios); ?>
                <?=Html::a(null, null, [
                    'id' => 'like' . $model->id,
                    'class' => 'text-danger fa-heart enlace ml-2',
                    'data-pjax' => 0
                ])?>
                <?=Html::tag('span', '', ['id' => 'numLikes' . $model->id]); ?>
                <?php if ($model->totalLikes > 0) : ?>
                (<?php
                foreach ($model->likes as $fila) : ?>
                    <?=$model->getUsuarioLike(['id' => $fila['usuario_id']])->username?>
                <?php endforeach ?>
                )
                    <?= Html::button('+', [
                    'class' => 'btn-ajax-modal enlace ml-2',
                    'value' => Url::to(['likes/index', 'id' => $model->id]),
                    'data-target' => '#modal_likes',
                ]);
                
                Modal::begin([
                    'id' => 'modal_likes',
                ]);
                echo '<div class="modal-content"></div>';
                Modal::end();
                ?>
                <?php endif ?>
                <?=Html::a('', null, [
                    'id' => 'guardado' . $model->id,
                    'class' => 'text-dark fa-bookmark ml-2',
                    'data-pjax' => 0
                ])?>
                <?=Html::a(Icon::show('download', ['framework' => Icon::FAS]), ['publicaciones/download', 'fichero' => $model->imagenUrl, 'id' => $model->usuario_id], ['class' => 'enlace ml-2']); ?>
            </div>
            <?php 
            if ($model->descripcion != '') : ?>
                <div class="card-body d-flex justify-content-between align-items-center comentario">
                    <?=Html::tag('p', Html::a($model->usuario->username, ['usuarios/perfil', 'id' => $model->usuario->id]) . ' ' . $model->descripcion)?>
                </div>
            <?php endif; ?>
            <div class="comentarios">
                <?php
                $filas = $model->getComentarios()->all();
                if (count($filas) == 1) :
                    $fila = $model->getComentarios()->one();
                    $usuario =  $model->getUsuarioComentario($fila['usuario_id']);
                    ?>
                    <div class="card-body d-flex justify-content-between align-items-center comentario">
                        <?=Html::tag('p', Html::a($usuario->username, ['usuarios/perfil', 'id' => $usuario->id]) . ' ' . $fila['comentario'])?>
                        <?php if (Yii::$app->user->identity->username === $usuario->username || Usuarios::isAdmin()) : ?>
                            <button class="btn dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <?=Icon::show('ellipsis-v', ['framework' => Icon::FA])?>
                            </button>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                <?=Html::a(Icon::show('edit', ['framework' => Icon::FA]) . 'Modificar', ['comentarios/update', 'id' =>  $fila['id']], ['class' => 'dropdown-item']);?>
                                <?=Html::a(Icon::show('trash', ['framework' => Icon::FA]) . 'Borrar', ['comentarios/delete', 'id' => $fila['id']], [
                                    'class' => 'dropdown-item',
                                    'data' => [
                                        'confirm' => '¿Eliminar Comentario?',
                                        'method' => 'post',
                                    ],
                                    ])?>
                            </div>
                        <?php endif ?>
                    </div>
                <?php elseif ($filas >= 2) : 
                    $ultimos = $model->getUltimosComentarios()->all();
                    foreach ($ultimos as $comentario) :
                        $usuario = $model->getUsuarioComentario($comentario['usuario_id']);
                    ?>
                    <div class="card-body d-flex pt-0 pb-0 justify-content-between align-items-center comentario">
                        <?=Html::tag('p', Html::a($usuario->username, ['usuarios/perfil', 'id' => $usuario->id]) . ' ' . $comentario['comentario'])?>
                        <?php if (Yii::$app->user->identity->username === $usuario->username || Usuarios::isAdmin()) : ?>
                            <button class="btn dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <?=Icon::show('ellipsis-v', ['framework' => Icon::FA])?>
                            </button>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                <?=Html::a(Icon::show('edit', ['framework' => Icon::FA]), ['comentarios/update', 'id' =>  $comentario['id']], ['class' => 'dropdown-item']);?>
                                <?=Html::a(Icon::show('trash', ['framework' => Icon::FA]), ['comentarios/delete', 'id' => $comentario['id']], [
                                    'class' => 'dropdown-item',
                                    'data' => [
                                        'confirm' => '¿Eliminar Comentario?',
                                        'method' => 'post',
                                    ],
                                ])?>
                            </div>
                        <?php endif ?>
                    </div>
                    <?php endforeach; ?>
                <?php endif ?>
            </div>
            <div class="d-flex justify-content-end align-items-center mb-4 mr-5">
                <?=Html::a('Nuevo comentario', ['comentarios/create', 'id' => $model->id], ['class' => 'btn btn-success']); ?>
            </div>
        </div>
    </div>
</div>