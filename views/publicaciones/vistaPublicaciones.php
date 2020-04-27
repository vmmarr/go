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
                <?=Icon::show('heart', ['framework' => Icon::BSG])?>
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