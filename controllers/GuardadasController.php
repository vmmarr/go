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
     * Lists all Guardadas models.
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
     * Creates a new Guardadas model.
     * If creation is successful, the browser will be redirected to the 'view' page.
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
     * Deletes an existing Guardadas model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Guardadas model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Guardadas the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Guardadas::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
