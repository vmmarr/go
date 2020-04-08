<?php

namespace app\controllers;

use app\models\ImagenForm;
use app\models\Usuarios;
use app\models\UsuariosSearch;
use Yii;
use yii\bootstrap4\Alert;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;

class UsuariosController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs'  => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
            'access' => [
                'class' => AccessControl::class,
                'only' => ['registrar'],
                'rules' => [
                    // allow authenticated users
                    [
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    // everything else is denied by default
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        $searchModel = new UsuariosSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

     /**
     * Displays a single Usuarios model.
     * @param int $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionPerfil($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    public function actionRegistrar()
    {
        $model = new Usuarios(['scenario' => Usuarios::SCENARIO_CREAR]);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $url = Url::to([
                'usuarios/activar',
                'id' => $model->id,
                'token' => $model->token,
            ], true);
            
            $body = <<<EOT
                <p>Pulsa el siguiente enlace para confirmar la cuenta de correo.<p>
                <a href="$url">Confirmar</a>
            EOT;

            $this->enviarEmail($body, $model->email);
            Yii::$app->session->setFlash('success', 'Se ha creado el usuario correctamente.');
            return $this->redirect(['site/login']);
        }

        return $this->render('registrar', [
            'model' => $model,
        ]);
    }

    public function enviarEmail($body, $correo)
    {
        Yii::$app->mailer->compose()
            ->setFrom(Yii::$app->params['smtpUsername'])
            ->setTo($correo)
            ->setSubject('Confirmar registro Go!')
            ->setTextBody($body)
            ->send();
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        
        $model->scenario = Usuarios::SCENARIO_UPDATE;
        
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'Se ha modificado correctamente.');
            return $this->redirect(['perfil', 'id' => $model->id]);
        }
        
        $model->password = '';
        $model->password_repeat = '';
        
        return $this->render('update', [
            'model' => $model,
            ]);
    }

    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $model->delete();
        Yii::$app->session->setFlash('success', 'Se ha borrado correctamente.');
        return $this->redirect(['site/login']);
    }

    public function actionActivar($id, $token)
    {
        $usuario = $this->findModel($id);
        if ($usuario->token === $token) {
            $usuario->token = null;
            $usuario->save();
            Yii::$app->session->setFlash('success', 'Usuario validado. Inicie sesión.');
            return $this->redirect(['site/login']);
        }
        Yii::$app->session->setFlash('error', 'La validación no es correcta.');
        return $this->redirect(['site/login']);
    }

    public function actionSubida($id)
    {
        $model = new ImagenForm();

        if (Yii::$app->request->isPost) {
            
            $model->imagen = UploadedFile::getInstance($model, 'imagen');
            if ($model->subida($id)) {
                Yii::$app->session->setFlash('success', 'Imagen subida con exito');
                return $this->redirect('usuarios/view');
            }
        }

        return $this->render('imagen', [
            'model' => $model,
        ]);
    }

    public function actionBorrarImagen()
    {
        $model = new Usuarios();

        $image = $model->getImage();
        $model->removeImage($image);
    }
    
    protected function findModel($id)
    {
        if (($model = Usuarios::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
