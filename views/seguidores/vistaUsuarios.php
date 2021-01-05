<?php

use app\models\Usuarios;
use yii\helpers\Html;
use yii\helpers\Url;

$url = Url::to(['seguidores/seguir', 'usuario_id' => Yii::$app->user->identity->id, 'seguidor_id' => $model->seguidor_id]);
$jsSeguir = <<<EOT
    $(document).ready(function () {
        $.ajax({
            type: 'GET',
            url: '$url',
            success: function (data) {
                data = JSON.parse(data);
                $('#btnSeguir' + '$model->seguidor_id').removeClass('btn-primary');
                $('#btnSeguir' + '$model->seguidor_id').removeClass('btn-outline-dark');
                $('#btnSeguir' + '$model->seguidor_id').addClass(data.class);
                $('#btnSeguir' + '$model->seguidor_id').text(data.text);
            }
        });
        $('#btnSeguir' + '$model->seguidor_id').click(function (e) {
            e.preventDefault();
            $.ajax({
                type: 'POST',
                url: '$url',
                success: function (data) {
                    data = JSON.parse(data);
                    $('#btnSeguir' + '$model->seguidor_id').removeClass('btn-outline-dark');
                    $('#btnSeguir' + '$model->seguidor_id').removeClass('btn-primary');
                    $('#btnSeguir' + '$model->seguidor_id').addClass(data.class);
                    $('#btnSeguir' + '$model->seguidor_id').text(data.text);
                }
            })
        });
    });
EOT;

$this->registerJs($jsSeguir);
$archivo = Usuarios::comprobarImagen($model->seguidor_id . '.png');
?>

<div class="row d-flex justify-content-between horizontal">
    <div class="col-3 justify-content-center mt-3">
        <?php if ($archivo) : ?>
                <?=Html::img(Usuarios::enlace($model->seguidor_id . '.png'))?>
            <?php  else : ?>
                <?=Html::img(Usuarios::enlace('perfil.png'))?>
        <?php endif; ?>
    </div>
    <div class="col-3 mt-5">
        <?php if ($model->seguidor_id === Yii::$app->user->id) : ?>
            <?=Html::a(Html::encode($model->usuario->username), ['usuarios/perfil', 'id' => $model->usuario_id])?>
        <?php else : ?>
            <?=Html::a(Html::encode($model->seguidor->username), ['usuarios/perfil', 'id' => $model->seguidor_id])?>
        <?php endif ?>
    </div>
    <div class="col-5 mt-5 mb-3">
        <?php if (!Usuarios::estaBloqueado($model->seguidor_id)) : ?>
            <?=Html::a('Seguir', null, [
                'id' => 'btnSeguir' . $model->seguidor_id,
                'class' => 'btn btn-primary',
                'data-pjax' => 0
            ])?>      
        <?php endif ?>
    </div>
</div>