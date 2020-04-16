<?php
use yii\helpers\Html;

?>

<div class="row d-flex justify-content-center align-items-center">
        <div class="col-md-8">
                <div class="card">
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
                    <?=Yii::$app->formatter->asRelativeTime($model->update_at)?>
                </div>
                <div class="contenido">
                    <?=Html::img('/img/perfil.png');?>
                </div>
                <div class="card-body d-flex justify-content-between align-items-center">
                    <!-- Aque va el numero de likes y el numero de comentarios -->
                </div>
                <div>
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