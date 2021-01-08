<?php

/* @var $this \yii\web\View */
/* @var $content string */

use app\widgets\Alert;
use yii\helpers\Html;
use yii\bootstrap4\Nav;
use yii\bootstrap4\NavBar;
use yii\bootstrap4\Breadcrumbs;
use app\assets\AppAsset;
use app\models\ImagenForm;
use app\models\User;
use app\models\Usuarios;
use kartik\icons\Icon;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php $this->registerCsrfMetaTags() ?>
    <link rel="shortcut icon" href="logo.ico" type="image/x-icon" />
    <title><?= Html::encode(Yii::$app->name) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<div class="wrap">
    <?php
        $username = '';
        $imagen;
        $aws = Yii::$app->awssdk->getAwsSdk();
        $s3 = $aws->createS3();
        $bukect = 'go00';
        $existe = $s3->doesObjectExist($bukect, Yii::$app->user->id . '.*');
    
    if ($existe) :
        $imagen = Usuarios::enlace(Yii::$app->user->id . '.*');
        //$imagen = ['usuarios/download', 'fichero' => Yii::$app->user->id . '.jpg'];
    else :
        $imagen = Usuarios::enlace('perfil.png');
    endif;

    if (!Yii::$app->user->isGuest) {
        $username = Yii::$app->user->identity->username;
    }
    NavBar::begin([
        'brandLabel' => Html::img('@web/logo.ico') . Yii::$app->name,
        'brandUrl' => ['/publicaciones/index'],
        'options' => [
            'class' => 'navbar-light bg-light navbar-expand-md fixed-top',
        ],
        'collapseOptions' => [
            'class' => 'justify-content-end',
        ],
        ]);
        echo Nav::widget([
            'options' => ['class' => 'navbar-nav justify-content-end'],
            'items' => [
                ['label' => Icon::show('home', ['framework' => Icon::FAS]), 'url' => ['/publicaciones/index'], 'visible' => !Yii::$app->user->isGuest],
                ['label' => 'Usuarios', 'url' => ['/usuarios/index'], 'visible' => !Yii::$app->user->isGuest && !Usuarios::isAdmin()],
                ['label' => 'Usuarios', 'url' => ['/usuarios/allusuarios'], 'visible' => Usuarios::isAdmin()],
                ['label' => 'Guardadas', 'url' => ['/guardadas/index'], 'visible' => !Yii::$app->user->isGuest],
                ['label' => 'Login', 'url' => ['/site/login'], 'visible' => Yii::$app->user->isGuest],
                ['label' => 'Registrarse', 'url' => ['/usuarios/registrar'], 'visible' => Yii::$app->user->isGuest],
                [
                    'options' => ['class' => 'foto'],
                    'label' => Html::img($imagen) . $username,
                    'items' => [
                        ['label' => 'Mi perfil', 'url' => ['usuarios/perfil', 'id' => Yii::$app->user->id]],
                        Html::beginForm(['/site/logout'], 'post')
                        . Html::submitButton(
                            'Logout (' . $username . ')',
                            ['class' => 'btn btn-ligth nav-link logout']
                        )
                        . Html::endForm()
                    ],
                    'visible' => !Yii::$app->user->isGuest
                ]
                ],
                'encodeLabels' => false
        ]);
    NavBar::end(); ?>

    <div class="container">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= Alert::widget() ?>
        <?= $content ?>
    </div>
</div>

<!-- <footer class="footer">
    <div class="container">
        <p class="float-left">&copy; My Company <?= date('Y') ?></p>

        <p class="float-right"><?= Yii::powered() ?></p>
    </div>
</footer> -->

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
