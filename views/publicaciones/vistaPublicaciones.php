<?php

use app\models\Comentarios;
use kartik\icons\Icon;
use yii\helpers\Html;
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
                <div>
                    <?=Yii::$app->formatter->asRelativeTime($model->created_at)?>
                    <button class="btn dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <?=Icon::show('option-vertical', ['framework' => Icon::BSG])?>
                    </button>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                        <?=Html::a(Icon::show('pencil', ['framework' => Icon::BSG]), ['update', 'id' => $model->id], ['class' => 'dropdown-item']);?>
                        <?=Html::a(Icon::show('trash', ['framework' => Icon::BSG]), ['delete', 'id' => $model->id], [
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
                <?=Icon::show('comment', ['framework' => Icon::BSG])?>
                <?=Html::tag('span', $model->totalComentarios); ?>
                <!-- Si el usuario logueado es el mismo que el usuario id de un like y le a dado a like se pone heart si no heart-empty -->
                <?php
                $filas = $model->likes;
                $r = false;
                $id_like = 0;

                foreach ($filas as $like) :
                    if (Yii::$app->user->id === $like['usuario_id']) :
                        $r = true;
                        $id_like = $like['id'];
                    endif;
                endforeach;
                    
                if ($r) : ?>
                    <?=Html::a(Icon::show('heart', ['framework' => Icon::BSG]), ['likes/delete', 'id' => $id_like]);?>
                <?php else : ?>
                    <?=Html::a(Icon::show('heart-empty', ['framework' => Icon::BSG]), ['likes/create', 'id' => $model->id]);?>
                <?php endif; ?>
                <?=Html::tag('span', $model->totalLikes); ?>
            </div>
            <div>
                <?=Html::tag('p', Html::a($model->usuario->username, ['usuarios/perfil', 'id' => $model->usuario->id]) . ' ' . $model->descripcion)?>
            </div>
            <div>
                <!-- Muestra los 2 ultimos comentarios -->
                <?php
                $filas = $model->comentarios;

                foreach ($filas as $comentario) :
                    $usuario =  $model->getUsuarioComentario($comentario['usuario_id']);
                ?>
                <div class="card-body d-flex justify-content-between align-items-center">

                    <?=Html::tag('p', Html::a($usuario->username, ['usuarios/perfil', 'id' => $usuario->id]) . ' ' . $comentario['comentario'])?>
                    <button class="btn dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <?=Icon::show('option-vertical', ['framework' => Icon::BSG])?>
                    </button>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                        <?=Html::a(Icon::show('pencil', ['framework' => Icon::BSG]), ['comentarios/update', 'id' =>  $comentario['id']], ['class' => 'dropdown-item']);?>
                        <?=Html::a(Icon::show('trash', ['framework' => Icon::BSG]), ['comentarios/delete', 'id' => $comentario['id']], [
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