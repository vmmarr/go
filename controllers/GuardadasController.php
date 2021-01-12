<?php

namespace app\controllers;

use Yii;
use app\models\Guardadas;
use app\models\GuardadasSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * GuardadasController implements the CRUD actions for Guardadas model.
 */
class GuardadasController extends Controller
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
     * Lista de todas las Guardadas
     * @return mixed
     */
    public function actionIndex()
    {
        if (!Yii::$app->user->isGuest) {
            $searchModel = new GuardadasSearch();
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

            return $this->render('index', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
            ]);
        } else {
            return $this->redirect(['/site/login']);
        }
    }

    /**
     * Crear Guardada
     * @return mixed
     */
    public function actionCreate($publicacion_id)
    {        
        $model = new Guardadas();
        $existe = $model->find()->where(['usuario_id' => Yii::$app->user->identity->id, 'publicacion_id' => $publicacion_id])->exists();
        if (Yii::$app->request->isAjax && Yii::$app->request->isGet) {
            if ($existe) {
                return json_encode(['class' => 'fas']);
            } else {
                return json_encode(['class' => 'far']);
            }
        }

        if ($existe && $model->find()->where(['usuario_id' => Yii::$app->user->identity->id, 'publicacion_id' => $publicacion_id])->one()->delete()) {
            if (Yii::$app->request->isAjax && Yii::$app->request->isPost) {
                return json_encode(['class' => 'far']);
            }
        } else {
            $model->usuario_id = Yii::$app->user->identity->id;
            $model->publicacion_id = $publicacion_id;
            if ($model->save()) {
                return json_encode(['class' => 'fas']);
            }
        }
    }

    /**
     * Borrar una Guardada existente
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException Si no se encuentra
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Buscar en las Guardadas a traves del id
     * @param integer $id
     * @return Guardadas the loaded model
     * @throws NotFoundHttpException si no se encuentra
     */
    protected function findModel($id)
    {
        if (($model = Guardadas::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
