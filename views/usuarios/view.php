<?php

use app\models\Usuarios;
use kartik\icons\Icon;
use yii\bootstrap4\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Usuarios */


// $this->registerCss('body { background: #f00; }');
$this->title = $model->username;
$this->registerCssFile('@web/css/perfil.css');
// $this->registerCssFile('@web/css/perfil.css', ['depends' => [yii\bootstrap\BootstrapAsset::className()]]);
// \yii\web\YiiAsset::register($this);
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
                            <h1 class="profile-user-name"><?= Html::encode($this->title) ?></h1>
                            
                            <!-- if (Yii::$app->user->id === $model->id) {?> -->                    
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
                    </div>
                    
                    <div class="profile-stats">
                        <ul>
                            <li><span class="profile-stat-count"><?=count($model->publicaciones)?></span> publicaciones</li>
                            <li><span class="profile-stat-count"><?=count($model->seguidos)?></span> seguidores</li>
                            <li><span class="profile-stat-count"><?=count($model->seguidores)?></span> seguidos</li>
                        </ul>
                    </div>
                    <div class="profile-bio">

                        <h4><?= Html::encode($model->nombre) ?></h4>
                        <br>
                        <?= Html::tag('p', Html::encode($model->biografia)) ?>
                    </div>
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
                    <div class="col-4 col-md-6 col-lg-10 mb-2">
                        <div class="gallery-item" tabindex="0">
                            <?= Html::img(Usuarios::enlace($fila->imagenUrl), ['class' => 'gallery-image']) ?>
                            <div class="gallery-item-info">
                                <ul>
                                    <?= Html::tag('li', Icon::show('heart', ['framework' => Icon::FAR]), ['class' => 'd-inline-block']) ?>
                                 <?= Html::tag('li', Icon::show('comment', ['framework' => Icon::FA]), ['class' => 'd-inline-block']) ?>
                                </ul>
                            </div>
                        </div>
                    </div>
            <?php 
                endforeach;
            else : ?>
            <div class="row justify-content-center">
                <div class="col-12">
                    <?= Html::img('@web/sinPublicaciones.png') ?>
                </div>
            </div>
            <?php endif ?>
        </div>
    </main>
</div>