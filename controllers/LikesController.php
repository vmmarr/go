<?php

namespace app\controllers;

use app\models\Likes;
use app\models\LikesSearch;
use app\models\Publicaciones;
use app\models\Usuarios;
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

    // public function actionCreate($id)
    // {
    //     $model = new Likes();
    //     $publi = $this->findPublicacion($id);

    //     if ($model->load(Yii::$app->request->post()) && $model->save()) {
    //         Yii::$app->session->setFlash('success', 'Like añadido.');
    //         return $this->redirect(['publicaciones/index']);
    //     }

    //     return $this->render('create', [
    //         'model' => $model,
    //         'publi' => $publi,
    //     ]);
    // }

    public function actionLikes($usuario_id, $publicacion_id) 
    {
        
        $model = new Likes();
        $existe = $model->find()->where(['usuario_id' => Yii::$app->user->identity->id, 'publicacion_id' => $publicacion_id])->exists();
        // if (Usuarios::Bloqueado($model->usuario_id)) {
            // $existeLike = Likes::find()
            // ->andwhere(['usuario_id' => Yii::$app->user->identity->id])
            // ->andWhere(['publicacion_id' => $publicacion_id])
            // ->exists();
        // }
// if (!Usuarios::estaBloqueado($usuario_id)) :
    // if ($existeLike) :
    //     Likes::find()
    //     ->andwhere(['usuario_id' => Yii::$app->user->identity->id])
    //     ->andWhere(['publicacion_id' => $publicacion_id])
    //     ->one()->delete();
    // endif;
    if (Yii::$app->request->isAjax && Yii::$app->request->isGet) {
        if ($existe) {
                    $j = $model->find()->where(['publicacion_id' => $publicacion_id])->count();
                    return json_encode(['class' => 'fas', 'contador' => $j]);
                } else {
                    $j = $model->find()->where(['publicacion_id' => $publicacion_id])->count();
                    return json_encode(['class' => 'far', 'contador' => $j]);
                }
            }

            if ($existe && $model->find()->where(['usuario_id' => Yii::$app->user->identity->id, 'publicacion_id' => $publicacion_id])->one()->delete()) {
                if (Yii::$app->request->isAjax && Yii::$app->request->isPost) {
                    $j = $model->find()->where(['publicacion_id' => $publicacion_id])->count();
                    return json_encode(['class' => 'far', 'contador' => $j]);
                }
            } else {
                $model->usuario_id = Yii::$app->user->identity->id;
                $model->publicacion_id = $publicacion_id;
                if ($model->save()) {
                    $j = $model->find()->where(['publicacion_id' => $publicacion_id])->count();
                    return json_encode(['class' => 'fas', 'contador' => $j]);
                }
            }
        // endif;
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
