<?php

namespace app\controllers;

use app\models\FormRecoverPass;
use app\models\FormResetPass;
use app\models\ImagenForm;
use app\models\Usuarios;
use app\models\UsuariosSearch;
use Yii;
use yii\bootstrap4\ActiveForm;
use yii\bootstrap4\Alert;
use yii\data\ActiveDataProvider;
use yii\db\Query;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\web\UploadedFile;

use function GuzzleHttp\Promise\all;

class UsuariosController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
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
        $model = new Usuarios();

        $fila = Usuarios::find()->all();
            
        $cadena = Yii::$app->request->get('cadena', '');

        $query = Usuarios::find()
            ->filterWhere(['ilike', 'nombre', $cadena])
            ->orFilterWhere(['ilike', 'username', $cadena])
            ->all();

        return $this->render('index', [
            'cadena' => $cadena,
            'query' => $query,
            'fila' => $fila,
            'model' => $model,
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


        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $url = Url::to([
                'usuarios/activar',
                'id' => $model->id,
                'token' => $model->token,
            ], true);

            $body = <<< EOT
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
            ->setHtmlBody($body)
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
        var_dump('Estes en subir imagen perfil');

        if (Yii::$app->request->isPost) {
            $model->imagen = UploadedFile::getInstance($model, 'imagen');
            if ($model->subida($id) && $model->subidaAws($id)) {
                Yii::$app->session->setFlash('success', 'Imagen subida con exito');
                $model->borradoLocal($id);
                return $this->redirect('usuarios/view');
            }
        }

        return $this->render('imagen', [
            'model' => $model,
        ]);
    }

    public function actionDownload($fichero)
    {
        $model = new ImagenForm();
        $f = $model->descarga($fichero);

        header('Content-Type: ' . $f['ContentType']);
        echo $f['Body'];
    }

    // public function actionBorrarImagen()
    // {
    //     $model = new Usuarios();

    //     $image = $model->getImage();
    //     $model->removeImage($image);
    // }

    public function actionRecoverpass()
    {
        $model = new FormRecoverPass();

        if ($model->load(Yii::$app->request->post())) :
            if ($model->validate()) :
                $usuario = Usuarios::find()->where('email=:email', [':email' => $model->email]);

                if ($usuario->count() == 1) :
                    Yii::$app->session['recover'] = Yii::$app->security->generateRandomString(8);
                    // $recover = Yii::$app->session['recover'];

                    $usuario = Usuarios::findOne(['email' => $model->email]);
                    Yii::$app->session['id_recover'] = $usuario->id;

                    $verification_code = Yii::$app->security->generateRandomString(8);
                    $usuario->verification_code = $verification_code;
                    $usuario->save();
                    $url = Url::to([
                        'usuarios/resetpass',
                    ], true);

                    $body = <<< EOT
                        <p>Copie el siguiente código de verificación para restablecer su password ...
                        <strong>$verification_code</strong></p>
                        <p><a href="$url">Recuperar password</a></p>
                    EOT;

                    Yii::$app->mailer->compose()
                        ->setFrom(Yii::$app->params['smtpUsername'])
                        ->setTo($model->email)
                        ->setSubject('Restablecer contraseña Go!')
                        ->setHtmlBody($body)
                        ->send();

                    $model->email = null;

                    Yii::$app->session->setFlash('success', 'Le hemos enviado un mensaje a su cuenta de correo para que pueda resetear su contraseña');
                    return $this->redirect('site/login');
                else :
                    Yii::$app->session->setFlash('danger', 'No hay ningun usuario con ese email');
                    return $this->redirect('site/login');
                endif;
            endif;
        endif;

        return $this->render('recoverpass', [
            'model' => $model,
        ]);
    }

    public function actionResetpass()
    {
        $model = new FormResetPass();

        //Si no existen las variables de sesión requeridas lo expulsamos a la página de inicio
        if (empty(Yii::$app->session['recover']) || empty(Yii::$app->session['id_recover'])) :
            return $this->redirect(['site/login']);
        else :
            $recover = Yii::$app->session['recover'];
            $model->recover = $recover;

            $id_recover = Yii::$app->session['id_recover'];
        endif;

        //Si el formulario es enviado para resetear el password
        if ($model->load(Yii::$app->request->post())) :
            if ($model->validate()) :
                //Si el valor de la variable de sesión recover es correcta
                if ($recover == $model->recover) :
                    $usuario = Usuarios::findOne(['email' => $model->email]);
                    // var_dump($usuario);
                    $usuario->password = Yii::$app->security->generatePasswordHash($model->password);
                    //Si la actualización se lleva a cabo correctamente
                    if ($usuario->save()) :
                        //Destruir las variables de sesión
                        // $session->destroy();

                        //Vaciar los campos del formulario
                        $model->email = null;
                        $model->password = null;
                        $model->password_repeat = null;
                        $model->recover = null;
                        $model->verification_code = null;

                        Yii::$app->session->setFlash('success', 'Enhorabuena, password reseteado correctamente, redireccionando a la página de login');
                        return $this->redirect('site/login');
                    else :
                        Yii::$app->session->setFlash('danger', 'Ha ocurrido un error');
                        return $this->redirect('site/login');
                    endif;
                endif;
            endif;
        endif;

        return $this->render('resetpass', [
            'model' => $model
        ]);
    }


    protected function findModel($id)
    {
        if (($model = Usuarios::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
