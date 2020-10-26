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

$url = Url::to(['bloqueados/bloquear', 'usuario_id' => Yii::$app->user->identity->id, 'bloqueado_id' => $model->id]);
$js = <<<EOT
    $(document).ready(function () {
        $.ajax({
            type: 'GET',
            url: '$url',
            success: function (data) {
                data = JSON.parse(data);
                $('#btnBloquear' + '$model->id').removeClass('btn-danger');
                $('#btnBloquear' + '$model->id').removeClass('btn-outline-danger');
                $('#btnBloquear' + '$model->id').addClass(data.class);
                $('#btnBloquear' + '$model->id').text(data.text);
            }
        });
        $('#btnBloquear' + '$model->id').click(function (e) {
            e.preventDefault();
            $.ajax({
                type: 'POST',
                url: '$url',
                success: function (data) {
                    data = JSON.parse(data);
                    $('#btnBloquear' + '$model->id').removeClass('btn-outline-danger');
                    $('#btnBloquear' + '$model->id').removeClass('btn-danger');
                    $('#btnBloquear' + '$model->id').addClass(data.class);
                    $('#btnBloquear' + '$model->id').text(data.text);
                }
            })
        });
    });
EOT;

$this->registerJs($js);
$archivo = $model->comprobarImagen($model->id . '.png');
?>

<div class="row d-flex justify-content-between horizontal">
    <div class="col-md-2 justify-content-center mt-3">
        <?php if ($archivo) : ?>
                <?=Html::img(['download', 'fichero' => $model->id . '.png']);?>
            <?php  else : ?>
                <?=Html::img(['download', 'fichero' => 'perfil.png']);?>
        <?php endif; ?>
    </div>
    <div class="col-md-6 mt-5">
        <?=Html::a(Html::encode($model->username))?>
    </div>
    <div class="col-md-4 mt-5 mb-3">
        <?=Html::a('Bloquear', null, [
            'id' => 'btnBloquear' . $model->id,
            'class' => 'btn btn-danger',
            'data-pjax' => 0
        ])?>
        <?=Html::a('Seguir', null, [
            'id' => 'btnSeguir' . $model->id,
            'class' => 'btn btn-primary',
            'data-pjax' => 0
        ])?>
    </div>
</div>