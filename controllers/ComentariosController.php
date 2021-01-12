<?php

namespace app\controllers;

use app\models\Comentarios;
use app\models\ComentariosSearch;
use app\models\Publicaciones;
use app\models\Usuarios;
use Yii;
use yii\web\NotFoundHttpException;

class ComentariosController extends \yii\web\Controller
{
    /**
     * Muestra el index de los comentarios
     *
     * @return mixed
     */
    public function actionIndex($id)
    {
        $searchModel = new ComentariosSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $id);

        return $this->renderAjax('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    /**
     * Crea un nuevo comentario a una publicacion que se le pasa por parametro
     *
     * @return mixed
     */
    public function actionCreate($id)
    {
        $model = new Comentarios();
        $publi = $this->findPublicacion($id);
        if (!Usuarios::estaBloqueado($publi->usuario_id)) {
            if ($model->load(Yii::$app->request->post()) && $model->save()) {
                Yii::$app->session->setFlash('success', 'Comentario añadido.');
                return $this->redirect(['publicaciones/index']);
            }
        } else {
            Yii::$app->session->setFlash('danger', 'No puedes comentar, te tiene bloqueado.');
            return $this->redirect(['publicaciones/index']);
        }

        return $this->render('create', [
            'model' => $model,
            'publi' => $publi,
        ]);
    }

    /**
     * Actualizar un comentario pasado por parametro
     *
     * @param [type] $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findComentario($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'Se ha modificado correctamente.');
            return $this->redirect(['publicaciones/index']);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Borrar un comentario pasado por parametro
     *
     * @param [type] $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findComentario($id);
        $model->delete();

        Yii::$app->session->setFlash('success', 'Comentario borrado con éxito.');
        return $this->redirect(['publicaciones/index']);
    }

    /**
     * Busqueda de un comentario posado por parametro
     *
     * @param [type] $id
     * @return void
     */
    protected function findComentario($id)
    {
        if (($comentario = Comentarios::findOne($id)) === null) {
            throw new NotFoundHttpException('No se ha encontrado es comentario.');
        }

        return $comentario;
    }

    /**
     * Busca una publicacion pasada por parametro
     *
     * @param [type] $id
     * @return void
     */
    protected function findPublicacion($id)
    {
        if (($publicacion = Publicaciones::findOne($id)) === null) {
            throw new NotFoundHttpException('No se ha encontrado esa publicacion.');
        }

        return $publicacion;
    }
}
