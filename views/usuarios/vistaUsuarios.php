<?php

use app\models\Comentarios;
use kartik\icons\Icon;
use yii\helpers\Html;
use yii\helpers\Url;

// $url = Url::to(['likes/likes', 'usuario_id' => Yii::$app->user->identity->id, 'publicacion_id' => $model->id]);
// $js = <<<EOT
//     $(document).ready(function () {
//         $.ajax({
//             type: 'GET',
//             url: '$url',
//             success: function (data) {
//                 data = JSON.parse(data);
//                 $('#like' + '$model->id').removeClass('far');
//                 $('#like' + '$model->id').addClass(data.class);
//                 $('#numLikes' + '$model->id').text(data.contador);
//             }
//         });
//         $('#like' + '$model->id').click(function (e) {
//             e.preventDefault();
//             $.ajax({
//                 type: 'POST',
//                 url: '$url',
//                 success: function (data) {
//                     data = JSON.parse(data);
//                     $('#like' + '$model->id').removeClass('fas');
//                     $('#like' + '$model->id').addClass(data.class);
//                     $('#numLikes' + '$model->id').text(data.contador);
//                 }
//             })
//         });
//     });
// EOT;

// $this->registerJs($js);
$archivo = $model->comprobarImagen($model->id . '.png');
?>
<div class="row">
    <div class="col">
        <div class="card">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div class="fotoNombre">
                    <?php
                    if ($archivo) : ?>
                        <?=Html::img(['download', 'fichero' => $model->id . '.png']);?>
                    <?php  else : ?>
                        <?=Html::img(['download', 'fichero' => 'perfil.png']);?>
                    <?php endif; ?>

                    <?=Html::a($model->username)?>
                </div>
                <a href="#" class="btn btn-primary btn-sm">Seguir</a>
            </div>
        </div>
    </div>
</div>