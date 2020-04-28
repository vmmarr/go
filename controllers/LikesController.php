<?php

namespace app\controllers;

use app\models\Likes;
use app\models\LikesSearch;
use app\models\Publicaciones;
use Yii;
use yii\web\NotFoundHttpException;

class LikesController extends \yii\web\Controller
{
    public function actionIndex()
    {
        $searchModel = new LikesSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionCreate($id)
    {
        $model = new Likes();
        $publi = $this->findPublicacion($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'Like añadido.');
            return $this->redirect(['publicaciones/index']);
        }

        return $this->render('create', [
            'model' => $model,
            'publi' => $publi,
        ]);
    }

    public function actionDelete($id)
    {
        $model = $this->findLike($id);
        $model->delete();

        return $this->redirect(['publicaciones/index']);
    }

    protected function findLike($id)
    {
        if (($like = Likes::findOne($id)) === null) {
            throw new NotFoundHttpException('No se ha encontrado es comentario.');
        }

        return $like;
    }

    protected function findPublicacion($id)
    {
        if (($publicacion = Publicaciones::findOne($id)) === null) {
            throw new NotFoundHttpException('No se ha encontrado esa publicacion.');
        }

        return $publicacion;
    }
}
