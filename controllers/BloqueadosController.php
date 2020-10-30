<?php

namespace app\controllers;

use Yii;
use app\models\Bloqueados;
use app\models\BloqueadosSearch;
use app\models\Seguidores;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
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
     * Lists all Bloqueados models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new BloqueadosSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Bloqueados model.
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
     * Creates a new Bloqueados model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Bloqueados();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Bloqueados model.
     * If update is successful, the browser will be redirected to the 'view' page.
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
     * Deletes an existing Bloqueados model.
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
     * Finds the Bloqueados model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Bloqueados the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Bloqueados::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionBloquear($usuario_id, $bloqueado_id) 
    {
        $model = new Bloqueados();
        $existe = $model->find()->where(['usuario_id' => $usuario_id, 'bloqueado_id' => $bloqueado_id])->exists();
        $existeSeguidor = Seguidores::find()
        ->andwhere(['usuario_id' => $usuario_id])
        ->andWhere(['seguidor_id' => $bloqueado_id])
        ->exists();
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
