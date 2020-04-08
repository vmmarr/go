<?php

use kartik\icons\Icon;
use yii\bootstrap4\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Usuarios */


// $this->registerCss('body { background: #f00; }');
$this->title = $model->username;
$this->registerCssFile('@web/css/perfil.css');
// $this->registerCssFile('@web/css/perfil.css', ['depends' => [yii\bootstrap\BootstrapAsset::className()]]);
\yii\web\YiiAsset::register($this);
?>
<div class="usuarios-view">
    <header>
        <div class="container">
            <div class="profile">
                <div class="profile-image">
                    <?php
                    $archivo = '.' . $model->imagenUrl;
                
                    if (file_exists($archivo)) : ?>
                        <?=Html::img($model->imagenUrl, ['width' => 200, 'height' => 200]);?>
                    <?php  else : ?>
                        <?=Html::img('/img/perfil.png', ['width' => 200, 'height' => 200]);?>

                    <?php endif; ?>
                </div>

                <div class="profile-user-settings">
                    <h1 class="profile-user-name"><?= Html::encode($this->title) ?></h1>
                    
                    
                    <!-- if (Yii::$app->user->id === $model->id) {?> -->
                    <?=Html::a('Editar perfil', ['update', 'id' => $model->id], ['class' => 'btn btn-outline-secondary']);?>
                    <?=Html::a('Subir imagen perfil', ['subida', 'id' => $model->id], ['class' => 'btn btn-outline-secondary']);?>
                    <?=Html::a('Borrar usuario', ['delete', 'id' => $model->id], [
                        'class' => 'btn btn-outline-danger',
                        'data' => [
                        'confirm' => '¿Seguro que quieres eliminar el usuario?',
                        'method' => 'post',
                        ],
                    ])?>
                                     
                </div>
                
                <div class="profile-stats">
                    <ul>
                        <li><span class="profile-stat-count">164</span> publicaciones</li>
                        <li><span class="profile-stat-count">188</span> seguidores</li>
                        <li><span class="profile-stat-count">206</span> seguidos</li>
                    </ul>
                </div>
                <h4><?= Html::encode($model->nombre) ?></h4>
            </div>
        </div>
    </header>

    <main>
        <div class="container">
            <div class="gallery">
                <div class="gallery-item" tabindex="0">
                    <img src="https://images.unsplash.com/photo-1511765224389-37f0e77cf0eb?w=500&h=500&fit=crop" class="gallery-image" alt="">
                    <div class="gallery-item-info">
                        <ul>
                            <li><?=Icon::show('heart', ['framework' => Icon::BSG])?> Likes:</li>
                            <li><?=Icon::show('comment', ['framework' => Icon::BSG])?> Comentarios:</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>