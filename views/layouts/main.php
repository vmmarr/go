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
        $archivoj = './img/' . Yii::$app->user->id . '.jpg';
        $archivop = './img/' . Yii::$app->user->id . '.png';
        var_dump($archivoj);
    if (file_exists($archivoj)) :
        //var_dump('este');
        $imagen = $archivoj;
    elseif (file_exists($archivop)) :
        //var_dump('otro');
        // echo $archivo;
        $imagen = $archivop;
    else :
        $imagen = '/img/perfil.png';
    endif;

    if (!Yii::$app->user->isGuest) {
        $username = Yii::$app->user->identity->username;
    }
    //$n = Yii::$app->user->identity->username;
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
                ['label' => Icon::show('home', ['framework' => Icon::BSG]), 'url' => ['/publicaciones/index']],
                ['label' => 'Usuarios', 'url' => ['/usuarios/index'], 'visible' => !Yii::$app->user->isGuest],
                ['label' => 'Login', 'url' => ['/site/login'], 'visible' => Yii::$app->user->isGuest],
                ['label' => 'Registrarse', 'url' => ['/usuarios/registrar'], 'visible' => Yii::$app->user->isGuest],
                [
                    // va por aqui para poner imagen de perfil
                    //  'label' => '<img src="' . $imagen . '"/> ' . $username,
                    'options' => ['class' => 'foto'],
                    // 'label' => 'p',
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

<footer class="footer">
    <div class="container">
        <p class="float-left">&copy; My Company <?= date('Y') ?></p>

        <p class="float-right"><?= Yii::powered() ?></p>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
