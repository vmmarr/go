<?php

namespace app\controllers;

use app\models\FormRecoverPass;
use app\models\FormResetPass;
use app\models\Publicaciones;
use app\models\Usuarios;
use app\models\UsuariosSearch;
use Yii;
use yii\bootstrap4\ActiveForm;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\web\UploadedFile;

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

    /**
     * LIsta todos los usuarios
     *
     * @return mixed
     */
    public function actionIndex()
    {
        if (!Yii::$app->user->isGuest) {
            $searchModel = new UsuariosSearch();
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
            return $this->render('index', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
            ]);
        } else {
            return $this->redirect(['site/login']);
        }
        
    }

    /**
     * Muestra la vista de un usuario.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException si el modelo no ha sido encontrado
     */
    public function actionPerfil($id)
    {
        $publicaciones = new Publicaciones();
        if (Usuarios::estaBloqueado($id))  {
            Yii::$app->session->setFlash('danger', 'El usuario le tiene bloqueado y no deja ver su perfil.');
            return $this->goHome();
        }
        return $this->render('view', [
            'model' => $this->findModel($id),
            'publicaciones' => $publicaciones,
        ]);
    }

    /**
     * Registro de un usuario
     *
     * @return mixed
     */
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

    /**
     * Manda email
     *
     * @param string $body
     * @param string $correo
     * @return void
     */
    public function enviarEmail($body, $correo)
    {
        Yii::$app->mailer->compose()
            ->setFrom(Yii::$app->params['smtpUsername'])
            ->setTo($correo)
            ->setSubject('Confirmar registro Go!')
            ->setHtmlBody($body)
            ->send();
    }

    /**
     * Mdifica un usuario
     *
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        $model->scenario = Usuarios::SCENARIO_UPDATE;

        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'Se ha modificado correctamente.');
            if (Usuarios::isAdmin()) {
                return $this->redirect(['usuarios/allusuarios']);
            } else {
                return $this->redirect(['perfil', 'id' => $model->id]);
            }
        }

        $model->password = '';
        $model->password_repeat = '';

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Borrar un usuario
     *
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $model->delete();
        Yii::$app->session->setFlash('success', 'Se ha borrado correctamente.');
        if (Usuarios::isAdmin()) {
            return $this->redirect(['usuarios/allusuarios']);
        } else {
            return $this->redirect(['site/login']);
        }
    }

    /**
     * Activa un usuario recien registrado
     *
     * @param integer $id
     * @param string $token
     * @return void
     */
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

    /**
     * Sube la imagen de perfil 
     *
     * @param integer $id
     * @return mixed
     */
    public function actionSubida($id)
    {
        $model = $this->findModel($id);
        $model->scenario = Usuarios::SCENARIO_IMAGEN;

        if ($model->load(Yii::$app->request->post())) {
            $model->imagen = UploadedFile::getInstance($model, 'imagen');
            if ($model->save()) {

                if ($model->subida($id) && $model->subidaAws($id)) {
                    Yii::$app->session->setFlash('success', 'Subida con exito');
                    $model->borradoLocal();
                    return $this->redirect(['usuarios/perfil' , 'id' => $id]);
                }
            } else {
                Yii::debug($model->errors);
            }
        }

        return $this->render('imagen', [
            'model' => $model,
        ]);
    }

    /**
     * Recuperar contraseña
     *
     * @return void
     */
    public function actionRecoverpass()
    {
        $model = new FormRecoverPass();

        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }

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

    /**
     * Muestra todos los usuarios
     *
     * @return void
     */
    public function actionAllusuarios() 
    {
        if (!Yii::$app->user->isGuest) {
            if (Yii::$app->user->identity->username === 'admin') {
                $searchModel = new UsuariosSearch();
                $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
                
                return $this->render('/usuarios/allUsuarios', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,

                ]);
            } else {   
                Yii::$app->session->setFlash('danger', 'Usted no tienes permisos para administrar usuarios');
                return $this->redirect(['publicaciones/index']);
            }
        } else {
            return $this->redirect(['/site/login']);
        }
    }
    
    /**
     * Resetear la contraseña
     *
     * @return void
     */
    public function actionResetpass()
    {
        $model = new FormResetPass();

        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }

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

    /**
     * Busqueda de un usuario
     *
     * @param integer $id
     * @return void
     */
    protected function findModel($id)
    {
        if (($model = Usuarios::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
