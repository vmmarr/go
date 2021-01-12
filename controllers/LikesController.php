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
    /**
     * Lista todas los Likes.
     * @return mixed
     */
    public function actionIndex($id)
    {
        $searchModel = new LikesSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $id);

        return $this->renderAjax('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    /**
     * Guardar un Like
     *
     * @param integer $usuario_id
     * @param integer $publicacion_id
     * @return mixed
     */
    public function actionLikes($usuario_id, $publicacion_id) 
    {
        
        $model = new Likes();
        $existe = $model->find()->where(['usuario_id' => Yii::$app->user->identity->id, 'publicacion_id' => $publicacion_id])->exists();
        if (!Usuarios::estaBloqueado($usuario_id)) :
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
        endif;
    }

    /**
     * Busqueda de una publicacion por id
     *
     * @param integer $id
     * @return mixed
     */
    protected function findPublicacion($id)
    {
        if (($publicacion = Publicaciones::findOne($id)) === null) {
            throw new NotFoundHttpException('No se ha encontrado esa publicacion.');
        }

        return $publicacion;
    }
}
