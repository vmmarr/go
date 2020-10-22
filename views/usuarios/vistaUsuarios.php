<?php

use app\models\Comentarios;
use kartik\icons\Icon;
use yii\helpers\Html;
use yii\helpers\Url;

$url = Url::to(['seguidores/seguir', 'usuario_id' => Yii::$app->user->identity->id, 'seguidor_id' => $model->id]);
$js = <<<EOT
    $(document).ready(function () {
        $.ajax({
            type: 'GET',
            url: '$url',
            success: function (data) {
                data = JSON.parse(data);
                $('#btnSeguir' + '$model->id').removeClass('btn-primary');
                $('#btnSeguir' + '$model->id').removeClass('btn-outline-dark');
                $('#btnSeguir' + '$model->id').addClass(data.class);
                $('#btnSeguir' + '$model->id').text(data.text);
            }
        });
        $('#btnSeguir' + '$model->id').click(function (e) {
            e.preventDefault();
            $.ajax({
                type: 'POST',
                url: '$url',
                success: function (data) {
                    data = JSON.parse(data);
                    $('#btnSeguir' + '$model->id').removeClass('btn-outline-dark');
                    $('#btnSeguir' + '$model->id').removeClass('btn-primary');
                    $('#btnSeguir' + '$model->id').addClass(data.class);
                    $('#btnSeguir' + '$model->id').text(data.text);
                }
            })
        });
    });
EOT;

$this->registerJs($js);
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
                <?=Html::a('Seguir', null, [
                    'id' => 'btnSeguir' . $model->id,
                    'class' => 'btn btn-primary btn-sm',
                    'data-pjax' => 0
                ])?>
            </div>
        </div>
    </div>
</div>