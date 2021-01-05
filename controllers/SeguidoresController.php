<?php

namespace app\controllers;

use app\models\Seguidores;
use app\models\SeguidoresSearch;
use app\models\Usuarios;
use Yii;

class SeguidoresController extends \yii\web\Controller
{
    public function actionIndex($opcion)
    {
        $searchModel = new SeguidoresSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $opcion);

        return $this->renderAjax('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    public function actionSeguir($usuario_id, $seguidor_id) 
    {
        $model = new Seguidores();
        $existe = $model->find()->where(['usuario_id' => $usuario_id, 'seguidor_id' => $seguidor_id])->exists();
        if (!Usuarios::estaBloqueado($seguidor_id)) :
            if (!Usuarios::Bloqueado($seguidor_id)) :
                if (Yii::$app->request->isAjax && Yii::$app->request->isGet) {
                    if ($existe) {
                        return json_encode(['class' => 'btn-outline-dark', 'text' => 'Siguendo']);
                    } else {                    
                        return json_encode(['class' => 'btn-primary', 'text' => 'Seguir']);
                    }
                }

                if ($existe && $model->find()->where(['usuario_id' => $usuario_id, 'seguidor_id' => $seguidor_id])->one()->delete()) {
                    if (Yii::$app->request->isAjax && Yii::$app->request->isPost) {
                        return json_encode(['class' => 'btn-primary', 'text' => 'Seguir']);
                    }
                } else {
                    $model->usuario_id = $usuario_id;
                    $model->seguidor_id = $seguidor_id;
                    if ($model->save()) {
                        return json_encode(['class' => 'btn-outline-dark', 'text' => 'Siguendo']);
                    }
                }
            endif;
        endif;
    }

}
