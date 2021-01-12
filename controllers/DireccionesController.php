<?php

namespace app\controllers;

use Yii;
use app\models\Direcciones;
use app\models\DireccionesSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;
use yii\widgets\ActiveForm;

/**
 * DireccionesController implements the CRUD actions for Direcciones model.
 */
class DireccionesController extends Controller
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
     * Lista todas las direcciones.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new DireccionesSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Muestra la vista de una direccion.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Crea nueva Direccion.
     * Si la creaccion es correcta, el navegador nos redirecciona a la pagina view
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Direcciones();

        if ($model->load(Yii::$app->request->post())) {
            if(Yii::$app->request->post('ajax') === 'form-add-dir'){
                Yii::$app->response->format = Response::FORMAT_JSON;
                return ActiveForm::validate($model);      
            }

            if(Yii::$app->request->get('resp', false)){
                Yii::$app->response->format = Response::FORMAT_JSON;
                return $model;
            }
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Modifica una direccion existente.
     * Si la modificacion es correcta, el navegador nos redirecciona a la pagina view
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Busca uns direccion por id
     * @param integer $id
     * @return Direcciones the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Direcciones::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    /**
     * Buscar una direccion por nombre
     *
     * @param [type] $nombre
     * @return void
     */
    public function actionBuscar($nombre) {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $result = Direcciones::find()->where(['ilike', 'nombre', $nombre])
                                    ->select('nombre as label, id')
                                    ->asArray()
                                    ->all();

        array_unshift($result, ['label' => 'crear direccion', 'id' => null]);
        return $result;
    }
}
