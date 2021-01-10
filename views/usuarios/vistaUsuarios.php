<?php


use app\models\Usuarios;
use yii\helpers\Html;
use yii\helpers\Url;

$url = Url::to(['seguidores/seguir', 'usuario_id' => Yii::$app->user->identity->id, 'seguidor_id' => $model->id]);
$jsSeguir = <<<EOT
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


$url = Url::to(['bloqueados/bloquear', 'usuario_id' => Yii::$app->user->identity->id, 'bloqueado_id' => $model->id]);
$jsBloquear = <<<EOT
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
            var boton = $('#btnBloquear' + '$model->id').text();
            if (boton == 'Bloqueado') {
                $('#btnSeguir' + '$model->id').hide();
            } else {
                $('#btnSeguir' + '$model->id').removeClass('btn-outline-dark');
                $('#btnSeguir' + '$model->id').addClass(data.cs);
                $('#btnSeguir' + '$model->id').text(data.ts);
                $('#btnSeguir' + '$model->id').show();
            }
            
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
                var boton = $('#btnBloquear' + '$model->id').text();
                if (boton == 'Bloqueado') {
                    $('#btnSeguir' + '$model->id').hide();
                } else {
                    $('#btnSeguir' + '$model->id').removeClass('btn-outline-dark');
                    $('#btnSeguir' + '$model->id').addClass(data.cs);
                    $('#btnSeguir' + '$model->id').text(data.ts);
                    $('#btnSeguir' + '$model->id').show();
                }
            }
        })
    });
});
EOT;

$this->registerJs($jsSeguir);
$this->registerJs($jsBloquear);
$archivo = $model->comprobarImagen($model->id . '.png');
?>

<div class="row d-flex justify-content-between horizontal">
    <div class="col-md-2 justify-content-center mt-3">
        <?php if ($archivo) : ?>
                <?=Html::img(Usuarios::enlace($model->id . '.png'))?>
            <?php  else : ?>
                <?=Html::img(Usuarios::enlace('perfil.png'))?>
        <?php endif; ?>
    </div>
    <div class="col-md-6 mt-5" itemscope itemtype="https://schema.org/Person">
        <?=Html::a('<span itemprop="additionalName">' . Html::encode($model->username) . '</span>', ['usuarios/perfil', 'id' => $model->id])?>
    </div>
    <div class="col-md-4 mt-5 mb-3">
        <?=Html::a('Bloquear', null, [
            'id' => 'btnBloquear' . $model->id,
            'class' => 'btn btn-danger',
            'data-pjax' => 0
        ])?>
        <?php if (!Usuarios::estaBloqueado($model->id)) : ?>
            <?=Html::a('Seguir', null, [
                'id' => 'btnSeguir' . $model->id,
                'class' => 'btn btn-primary',
                'data-pjax' => 0
            ])?>      
        <?php endif ?>
    </div>
</div>