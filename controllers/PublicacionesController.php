<?php

namespace app\controllers;

use app\models\Comentarios;
use app\models\ImagenPublicacion;
use app\models\Publicaciones;
use SplObjectStorage;
use Yii;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;

class PublicacionesController extends \yii\web\Controller
{
    public function actionIndex()
    {
        $query = Publicaciones::find()->orderBy(['created_at' => SORT_DESC]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);


        // var_dump($comentarios->comentario);
        // return $comentarios['comentario'];

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionCreate()
    {
        $model = new Publicaciones();
        $model2 = new ImagenPublicacion();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $model2->imagen = UploadedFile::getInstance($model, 'imagen');
            if ($model2->subida($model->id) && $model2->subidaAws($model->id)) {
                Yii::$app->session->setFlash('success', 'Publicacion subida con exito');
                $model2->borradoLocal();
                return $this->redirect(['index']);
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

    // public function actionSubida($id)
    // {
    //     $model = new ImagenPublicacion();
    //     // var_dump('Estes en subir imagen publicacion');
        
    //     if (Yii::$app->request->isPost) {
    //         $model->imagen = UploadedFile::getInstance($model, 'imagen');
    //         if ($model->subida($id) && $model->subidaAws($id)) {
    //             Yii::$app->session->setFlash('success', 'Publicacion subida con exito');
    //             $model->borradoLocal();
    //             return $this->redirect(['index']);
    //         }
    //     }

    //     return $this->render('_form', [
    //         'model' => $model,
    //     ]);
    // }

    public function actionDownload($fichero)
    {
        $model = new ImagenPublicacion();
        $f = $model->descarga($fichero);
        //download the file
        header('Content-Type: ' . $f['ContentType']);
        echo $f['Body'];
    }

    public function actionDelete($id)
    {
        $model = $this->findPublicacion($id);
        $model->delete();
        $image = new ImagenPublicacion();
        $image->borradoAmazon($id);

        Yii::$app->session->setFlash('success', 'Publicacion borrada con éxito.');
        return $this->redirect(['index']);
    }

    protected function findPublicacion($id)
    {
        if (($publicacion = Publicaciones::findOne($id)) === null) {
            throw new NotFoundHttpException('No se ha encontrado esa publicacion.');
        }

        return $publicacion;
    }
}
