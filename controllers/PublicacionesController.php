<?php

namespace app\controllers;

use app\models\Publicaciones;
use app\models\PublicacionesSearch;
use Yii;

class PublicacionesController extends \yii\web\Controller
{
    public function actionIndex()
    {
        $searchModel = new PublicacionesSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionCreate()
    {
        $model = new Publicaciones();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }
}