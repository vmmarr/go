<?php

namespace app\controllers;

use app\models\Publicaciones;
use app\models\PublicacionesSearch;
use Yii;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;

class PublicacionesController extends \yii\web\Controller
{
    public function actionIndex()
    {
        if (!Yii::$app->user->isGuest) {
            $searchModel = new PublicacionesSearch();
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

            return $this->render('index', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
            ]);
        } else {
            return $this->redirect(['/site/login']);
        }
    }

    public function actionCreate()
    {
        $model = new Publicaciones();
        
        if ($model->load(Yii::$app->request->post())) {
            $model->imagen = UploadedFile::getInstance($model, 'imagen');
            if ($model->save()) {
                if ($model->subida($model->id) && $model->subidaAws($model->id)) {
                    Yii::$app->session->setFlash('success', 'Publicacion subida con exito');
                    $model->borradoLocal(Yii::$app->user->id);
                    return $this->redirect(['index']);
                }
            }
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    public function actionUpdate($id)
    {
        $model = $this->findPublicacion($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'Se ha modificado correctamente.');
            return $this->redirect(['index']);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    public function actionDownload($fichero, $id)
    {
        $model = new Publicaciones();
        $p = $model->descarga($fichero, $id);
        return Yii::$app->response->sendFile($p) && $model->borradoLocal($id);
    }

    public function actionDelete($id)
    {
        $model = $this->findPublicacion($id);
        $model->delete();
        $image = new Publicaciones();
        $image->borradoAmazon($id);

        Yii::$app->session->setFlash('success', 'Publicacion borrada con éxito.');
        return $this->redirect(['index']);
    }

    // public function actionDescargar($id){
    //     $model = $this->findPublicacion($id);
    //     $url = Publicaciones::enlace($model->imagenUrl);
    //     $file = file_get_contents($url);
        
    //     return Yii::$app->response->sendFile($url);
    // }

    protected function findPublicacion($id)
    {
        if (($publicacion = Publicaciones::findOne($id)) === null) {
            throw new NotFoundHttpException('No se ha encontrado esa publicacion.');
        }

        return $publicacion;
    }
}
