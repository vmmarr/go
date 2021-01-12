<?php

namespace app\controllers;

use Yii;
use app\models\Bloqueados;
use app\models\Comentarios;
use app\models\Likes;
use app\models\Publicaciones;
use app\models\Seguidores;
use yii\web\Controller;
use yii\filters\VerbFilter;

/**
 * BloqueadosController implements the CRUD actions for Bloqueados model.
 */
class BloqueadosController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Bloquear a un usuario
     *
     * @param [type] $usuario_id
     * @param [type] $bloqueado_id
     * @return void
     */
    public function actionBloquear($usuario_id, $bloqueado_id) 
    {
        $model = new Bloqueados();
        $existe = $model->find()->where(['usuario_id' => $usuario_id, 'bloqueado_id' => $bloqueado_id])->exists();
        $existeSeguidor = Seguidores::find()
        ->andwhere(['usuario_id' => $usuario_id])
        ->andWhere(['seguidor_id' => $bloqueado_id])
        ->exists();
        $publicacionesBlo = Publicaciones::publicacionLike($usuario_id);
        if (Yii::$app->request->isAjax && Yii::$app->request->isGet) {
            if ($existe) {
                return json_encode(['class' => 'btn-outline-danger', 'text' => 'Bloqueado', 'cs' => 'btn-primary', 'ts' => 'Seguir']);
            } else {
                return json_encode(['class' => 'btn-danger', 'text' => 'Bloquear', 'cs' => 'btn-primary', 'ts' => 'Seguir']);
            }
        }
        
        if ($existe && $model->find()->where(['usuario_id' => $usuario_id, 'bloqueado_id' => $bloqueado_id])->one()->delete()) {
            if (Yii::$app->request->isAjax && Yii::$app->request->isPost) {
                return json_encode(['class' => 'btn-danger', 'text' => 'Bloquear', 'cs' => 'btn-primary', 'ts' => 'Seguir']);
            }
        } else {
            $model->usuario_id = $usuario_id;
            $model->bloqueado_id = $bloqueado_id;
            if ($model->save()) {
                foreach ($publicacionesBlo as $fila) {
                    $existeComentario = Comentarios::find()
                    ->andwhere(['usuario_id' => $bloqueado_id])
                    ->andWhere(['publicacion_id' => $fila['id']])
                    ->exists();
                    $existeLike = Likes::find()
                    ->andwhere(['usuario_id' => $bloqueado_id])
                    ->andWhere(['publicacion_id' => $fila['id']])
                    ->exists();
                    if ($existeLike) {
                        Likes::find()
                        ->andwhere(['usuario_id' => $bloqueado_id])
                        ->andWhere(['publicacion_id' => $fila['id']])
                        ->one()->delete();
                    }

                    if ($existeComentario) {
                        $comentarios = Comentarios::find()
                        ->andwhere(['usuario_id' => $bloqueado_id])
                        ->andWhere(['publicacion_id' => $fila['id']])
                        ->all();
                        foreach ($comentarios as $comentario) {
                            $comentario->delete();
                        }
                    }
                }
                if ($existeSeguidor) :
                    Seguidores::find()
                    ->andwhere(['usuario_id' => $usuario_id])
                    ->andWhere(['seguidor_id' => $bloqueado_id])
                    ->one()->delete();
                endif;
                return json_encode(['class' => 'btn-outline-danger', 'text' => 'Bloqueado', 'cs' => 'btn-primary', 'ts' => 'Seguir']);
            }
        }
    }
}
