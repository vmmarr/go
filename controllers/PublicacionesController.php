<?php

namespace app\controllers;

class PublicacionesController extends \yii\web\Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }

}
