<?php

use app\models\Usuarios;
use kartik\icons\Icon;
use yii\bootstrap4\Html;
use yii\bootstrap4\Modal;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\Usuarios */

$this->registerCssFile('@web/css/perfil.css');

$js = <<<EOT
$(document).ready(function() {
    $('.btn-ajax-modal').click(function (){
        var elm = $(this),
            target = elm.attr('data-target'),
            ajax_body = elm.attr('value');
 
        $(target).modal('show')
            .find('.modal-content')
            .load(ajax_body);
    });
  });
EOT;

$this->registerJs($js);
?>
<div class="usuarios-view">
    <header>
        <div class="row">
            <div class="col-lg-12 col-md-6">
                <div class="profile">
                    <div class="profile-image">
                        <?php
                        $archivo = $model->comprobarImagen($model->imagenUrl);
                        if ($archivo) : ?>
                            <?=Html::img(Usuarios::enlace($model->imagenUrl))?>
                        <?php  else : ?>
                                <?=Html::img(Usuarios::enlace('perfil.png'))?>
                        <?php endif; ?>
                    </div>
                    <div class="profile-user-settings">
                        <h1 class="profile-user-name" itemscope itemtype="https://schema.org/Person">
                            <span itemprop="additionalName"> <?= Html::encode($model->username) ?> </span>
                        </h1>
                                            
                        <?php if(Yii::$app->user->id === $model->id || Usuarios::isAdmin()) : ?>
                            <button class="btn dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <?=Icon::show('user-cog', ['framework' => Icon::FAS])?>
                            </button>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                <?=Html::a('Editar perfil', ['update', 'id' => $model->id], ['class' => 'dropdown-item']);?>
                                <?=Html::a('Subir imagen perfil', ['subida', 'id' => $model->id], ['class' => 'dropdown-item']);?> 
                                <?=Html::a('Borrar usuario', ['delete', 'id' => $model->id], [
                                    'class' => 'dropdown-item',
                                    'data' => [
                                        'confirm' => '¿Seguro que quieres eliminar el usuario?',
                                        'method' => 'post',
                                    ],
                                    ])?>
                            </div>   
                    <?php endif;?>
                        </div>
                    
                    <div class="profile-stats">
                        <ul>
                            <li><span class="profile-stat-count"><?=count($model->publicaciones)?></span> publicaciones</li>
                            <li>
                                <?= Html::button('<span class="profile-stat-count">' . count($model->seguidos) . '</span> seguidores', [
                                    'class' => 'btn-ajax-modal enlace',
                                    'value' => Url::to(['seguidores/index', 'opcion' => 'seguidores']),
                                    'data-target' => '#modal_seguidores',
                                ]);

                                Modal::begin([
                                    'id' => 'modal_seguidores',
                                ]);
                                echo '<div class="modal-content"></div>';
                                Modal::end();?>
                            </li>
                            <li>
                                <?= Html::button('<span class="profile-stat-count">' . count($model->seguidores) . '</span> seguidos', [
                                    'class' => 'btn-ajax-modal enlace',
                                    'value' => Url::to(['seguidores/index', 'opcion' => 'seguidos']),
                                    'data-target' => '#modal_seguidos',
                                ]);

                                Modal::begin([
                                    'id' => 'modal_seguidos',
                                ]);
                                echo '<div class="modal-content"></div>';
                                Modal::end();?>
                            </li>
                        </ul>
                    </div>
                    <div class="profile-bio">
                        <h4><?= Html::encode($model->nombre) ?></h4>
                        <?php if ($model->biografia !== '') : ?>
                            <br>
                            <?= Html::tag('p', Html::encode($model->biografia)) ?>
                        </div>
                    <?php endif ?>
                </div>
            </div>
        </div>
    </header>

    <main>
        <?php 
        $p = $publicaciones->find()->where(['usuario_id' => $model->id])->orderBy(['created_at' => SORT_DESC])->all();
        $total = count($p);
        if ($total !== 0) : ?>
            <div class="row d-flex justify-content-center">
                <?php foreach ($p as $fila) : ?> 
                <div class="col-4 col-md-6 col-lg-8 mb-2">
                    <div class="gallery-item" tabindex="0">
                    <?php 
                    $array = explode('.', $fila->imagenUrl);
                    $ext = end($array);
                    if ($ext !== 'mp4') { ?>
                        <?=Html::img(Usuarios::enlace($fila->imagenUrl), ['class' => 'gallery-image'])?>
                    <?php } else { ?>
                        <video class="gallery-image" controls>
                            <source src="<?=Usuarios::enlace($fila->imagenUrl)?>" type="video/mp4">
                        </video> 
                    <?php } ?>
                    </div>
                </div>
            <?php 
                endforeach;
            else : ?>
            <div class="row d-flex justify-content-center">
                <div class="col-5">
                    <?= Html::img(Usuarios::enlace('sinPublicaciones.png'), ['class' => 'gallery-image']) ?>
                </div>
            </div>
        <?php endif ?>
    </main>
</div>