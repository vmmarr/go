<?php

use kartik\icons\Icon;
use yii\helpers\Html;

?>

<div class="row d-flex justify-content-center align-items-center">
        <div class="col-md-8">
                <div class="card">
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
                    <div class="card-body d-flex justify-content-between align-items-center">
                        
                        <div class="fotoNombre">
                            
                            <?php
                            $archivo = '.' . $model->usuario->imagenUrl;

                            if (file_exists($archivo)) : ?>
                                <?=Html::img($archivo);?>
                            <?php  else : ?>
                                <?=Html::img('/img/perfil.png');?>
                            <?php endif; ?>
                                
                        <?=Html::a($model->usuario->nombre)?>

                    </div>
                    <div>
                        <?=Yii::$app->formatter->asRelativeTime($model->created_at)?>

                    </div>
                </div>
                   
                
                <div class="contenido d-flex justify-content-center align-items-center">
                <?php
                    var_dump($model->imagenUrl);
                ?>
                <?= Html::img($model->imagenUrl); ?>
                </div>
                <div class="card-body d-flex justify-content-between align-items-center">
                    <!-- Aque va el numero de likes y el numero de comentarios -->
                </div>
                <div>
                    <?php
                    var_dump($model->descripcion);
                    ?>
                    <?=Html::tag('p', $model->descripcion)?>
                </div>
                <div>
                    <!-- Muestra los 2 ulstimos comentarios -->
                </div>
                <div>
                    <!-- habra un formulario oculto para añadir comentario -->
                </div>
            </div>
        </div>
    </div>
    <!-- </div> -->
</div>