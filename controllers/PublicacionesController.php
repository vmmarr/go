<?php

namespace app\controllers;

use app\models\ImagenPublicacion;
use app\models\Publicaciones;
use Yii;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;

class PublicacionesController extends \yii\web\Controller
{
    public function actionIndex()
    {
        $query = Publicaciones::find();
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionCreate()
    {
        $model = new Publicaciones();
        $imagenModel = new ImagenPublicacion();

        // if (Yii::$app->request->isPost) {
        //     $model->imagen = UploadedFile::getInstance($model, 'imagen');
        //     if ($model->subida()) {
        //         Yii::$app->session->setFlash('success', 'Imagen subida con exito');
        //         return $this->redirect(['create']);
        //     }
        // }

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $imagenModel->imagen = UploadedFile::getInstance($imagenModel, 'imagen');
            if ($imagenModel->subida()) {
                Yii::$app->session->setFlash('success', 'Imagen subida con exito');
            }
            return $this->redirect(['index']);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }
    // public function actionSubida()
    // {
    //     $model = new ImagenPublicacion();
    //     var_dump('Estes en subir imagen publicacion');

    //     if (Yii::$app->request->isPost) {
    //         $model->imagen = UploadedFile::getInstance($model, 'imagen');
    //         if ($model->subida()) {
    //             Yii::$app->session->setFlash('success', 'Imagen subida con exito');
    //             return $this->redirect(['create']);
    //         }
    //     }

    //     return $this->render('imagen', [
    //         'model' => $model,
    //     ]);
    // }

    public function actionDelete($id)
    {
        $model = $this->findPublicacion($id);
        $model->delete();
        Yii::$app->session->setFlash('success', 'Fila borrada con éxito.');
        return $this->redirect(['index']);
    }

    protected function findPublicacion($id)
    {
        if (($publicacion = Publicaciones::findOne($id)) === null) {
            throw new NotFoundHttpException('No se ha encontrado el género.');
        }

        return $publicacion;
    }
}
