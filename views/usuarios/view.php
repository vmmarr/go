<?php

use yii\bootstrap4\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Usuarios */

$this->title = $model->username;
\yii\web\YiiAsset::register($this);
?>
<link rel="stylesheet" href="perfil.css">
<div class="usuarios-view">
    <header>
        <div class="container">
            <div class="profile">
                <div class="profile-image">
                    <img src="https://images.unsplash.com/photo-1513721032312-6a18a42c8763?w=152&h=152&fit=crop&crop=faces" alt="">
                </div>

                <div class="profile-user-settings">
                    <h1 class="profile-user-name"><?= Html::encode($this->title) ?></h1>
                    <p>
                        <?= Html::a('Editar perfil', ['update', 'id' => $model->id], ['class' => 'btn profile-edit-btn']) ?>
                    </p>
                    <button class="btn profile-settings-btn" aria-label="profile settings"><i class="fas fa-cog" aria-hidden="true"></i></button>
                </div>

                <div class="profile-stats">
                    <ul>
                        <li><span class="profile-stat-count">164</span> posts</li>
                        <li><span class="profile-stat-count">188</span> followers</li>
                        <li><span class="profile-stat-count">206</span> following</li>
                    </ul>
                </div>
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
                            <li class="gallery-item-likes"><span class="visually-hidden">Likes:</span><i class="fas fa-heart" aria-hidden="true"></i> 56</li>
                            <li class="gallery-item-comments"><span class="visually-hidden">Comments:</span><i class="fas fa-comment" aria-hidden="true"></i> 2</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>