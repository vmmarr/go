<?php

namespace app\controllers;

use app\models\Publicaciones;
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
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);

        if (!Yii::$app->user->isGuest) : 
            // var_dump($comentarios->comentario);
            // return $comentarios['comentario'];
            
            return $this->render('index', [
                'dataProvider' => $dataProvider,
                ]);
        else : 
            return $this->redirect(['/site/login']);
        endif;
    }

    public function actionCreate()
    {
        $model = new Publicaciones();
        
        if ($model->load(Yii::$app->request->post())) {
            $model->imagen = UploadedFile::getInstance($model, 'imagen');
            if ($model->save()) {
                if ($model->subida($model->id) && $model->subidaAws($model->id)) {
                    Yii::$app->session->setFlash('success', 'Publicacion subida con exito');
                    $model->borradoLocal();
                    return $this->redirect(['index']);
                }
            } //else {
               // Yii::info($model->errors);
            //}
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

    // public function actionDownload($fichero)
    // {
    //     $model = new Publicaciones();
    //     $f = $model->descarga($fichero);
    //     //download the file
    //     header('Content-Type: ' . $f['ContentType']);
    //     echo $f['Body'];
    // }

    public function actionDelete($id)
    {
        $model = $this->findPublicacion($id);
        $model->delete();
        $image = new Publicaciones();
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
