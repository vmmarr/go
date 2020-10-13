<?php

use app\models\Comentarios;
use kartik\icons\Icon;
use yii\helpers\Html;
use yii\helpers\Url;

$url = Url::to(['likes/likes', 'usuario_id' => Yii::$app->user->identity->id, 'publicacion_id' => $model->id]);
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

?>

<div class="row d-flex justify-content-center align-items-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div class="fotoNombre">
                    <?php
                    $archivo = $model->usuario->comprobarImagen($model->usuario->imagenUrl);
                    if ($archivo) : ?>
                        <?=Html::img(['download', 'fichero' => $model->usuario->imagenUrl]);?>
                    <?php  else : ?>
                        <?=Html::img(['download', 'fichero' => 'perfil.png']);?>
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
            <div class="contenido d-flex justify-content-center align-items-center">
                <?= Html::img(['download', 'fichero' => $model->imagenUrl]); ?>
            </div>
            <div>
                <?=Icon::show('comment', ['framework' => Icon::FAR])?>
                <?=Html::tag('span', $model->totalComentarios); ?>
                <!-- Si el usuario logueado es el mismo que el usuario id de un like y le a dado a like se pone heart si no heart-empty -->
                <?=Html::a(null, null, [
                    'id' => 'like' . $model->id,
                    'class' => 'text-danger fa-heart',
                    'data-pjax' => 0
                ])?>

                
                <?=Html::tag('span', '', ['id' => 'numLikes' . $model->id]); ?>
            </div>
            <div class="card-body d-flex justify-content-between align-items-center comentario">
                <?php 
                if ($model->descripcion != '') : ?>
                    <?=Html::tag('p', Html::a($model->usuario->username, ['usuarios/perfil', 'id' => $model->usuario->id]) . ' ' . $model->descripcion)?>
                <?php endif; ?>
            </div>
            <div class="comentarios">
                <!-- Muestra los 2 ultimos comentarios -->
                <?php
                $filas = $model->comentarios;

                foreach ($filas as $comentario) :
                    $usuario =  $model->getUsuarioComentario($comentario['usuario_id']);
                ?>
                <div class="card-body d-flex justify-content-between align-items-center comentario">

                    <?=Html::tag('p', Html::a($usuario->username, ['usuarios/perfil', 'id' => $usuario->id]) . ' ' . $comentario['comentario'])?>
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
                </div>
                <?php endforeach; ?>
            </div>
            <div class="d-flex justify-content-end align-items-center">
                <?=Html::a('Nuevo comentario', ['comentarios/create', 'id' => $model->id], ['class' => 'btn btn-success']); ?>
            </div>
        </div>
    </div>
</div>