<?php

namespace app\models;

use Yii;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "usuarios".
 *
 * @property int $id
 * @property string $nombre
 * @property string $username
 * @property string $email
 * @property string $password
 * @property string $authkey
 * @property float $activate
 *
 * @property Comentarios[] $comentarios
 * @property Likes[] $likes
 * @property Publicaciones[] $publicaciones
 * @property Seguidores[] $seguidores
 */
class Usuarios extends \yii\db\ActiveRecord implements IdentityInterface
{
    const SCENARIO_CREAR = 'crear';
    const SCENARIO_UPDATE = 'update';
    public $password_repeat;
    private $_imagen = null;
    private $_imagenUrl = null;
    public $verification_code;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'usuarios';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['nombre', 'email'], 'required'],
            [['username', 'password'], 'required',
                    'on' => [self::SCENARIO_DEFAULT, self::SCENARIO_CREAR],],
            [
                ['password'],
                'trim',
                'on' => [self::SCENARIO_CREAR, self::SCENARIO_UPDATE],
            ],
            [['token'], 'string', 'max' => 32],
            [['nombre', 'email', 'password', 'biografia', 'authkey'], 'string', 'max' => 255],
            [['username'], 'string', 'max' => 60],
            [['username', 'email'], 'unique'],
            [
                ['password_repeat'],
                'required',
                'on' => self::SCENARIO_CREAR
            ],
            [['nombre', 'email', 'biografia'], 'trim'],
            [['email'], 'email'],
            [
                ['password_repeat'],
                'compare',
                'compareAttribute' => 'password',
                'skipOnEmpty' => false,
                'on' => [self::SCENARIO_CREAR, self::SCENARIO_UPDATE],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'nombre' => 'Nombre',
            'username' => 'Nombre Usuario',
            'biografia' => 'Biografia',
            'email' => 'Email',
            'password' => 'Contraseña',
            'password_repeat' => 'Repetir contraseña',
            'authkey' => 'Authkey',
            'token' => 'Token',
        ];
    }

    public static function findIdentity($id)
    {
        return static::findOne($id);
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
    }

    public function getId()
    {
        return $this->id;
    }

    public function getImagen()
    {
        if ($this->_imagen !== null) {
            return $this->_imagen;
        }
        // Nube
        $this->setImagen($this->id . '.jpg');
        // Local
        // $this->setImagen(Yii::getAlias('@img/' . $this->id . '.png'));
        return $this->_imagen;
    }

    public function comprobarImagen($imagen)
    {
        $aws = Yii::$app->awssdk->getAwsSdk();
        $s3 = $aws->createS3();
        $bukect = 'go00';
        $f = $s3->doesObjectExist($bukect, $imagen);
        return $f;
    }

    public function setImagen($imagen)
    {
        $this->_imagen = $imagen;
    }

    public function getImagenUrl()
    {
        if ($this->_imagenUrl !== null) {
            return $this->_imagenUrl;
        }
        // Nube
        $this->setImagenUrl($this->id . '.jpg');
        // Local
        // $this->setImagenUrl(Yii::getAlias('@imgUrl/' . $this->id . '.png'));
        return $this->_imagenUrl;
    }

    public function setImagenUrl($imagenUrl)
    {
        $this->_imagenUrl = $imagenUrl;
    }

    public function getAuthKey()
    {
        return $this->authkey;
    }

    public function validateAuthKey($authKey)
    {
        return $this->authkey === $authKey;
    }

    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username]);
    }
 

    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password);
    }

    public function validateEmail($email)
    {
        return Yii::$app->security->validateEmail($email, $this->email);
    }

    public function beforeSave($insert)
    {
        if (!parent::beforeSave($insert)) {
            return false;
        }

        if ($insert) {
            if ($this->scenario === self::SCENARIO_CREAR) {
                $security = Yii::$app->security;
                $this->authkey = $security->generateRandomString();
                $this->token = $security->generateRandomString(32);
                $this->password = $security->generatePasswordHash($this->password);
            }
        } else {
            if ($this->scenario === self::SCENARIO_UPDATE) {
                if ($this->password === '') {
                    $this->password = $this->getOldAttribute('password');
                } else {
                    $this->password = Yii::$app->security->generatePasswordHash($this->password);
                }
            }
        }

        return true;
    }

    /**
     * Gets query for [[Seguidores]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSeguidores()
    {
        return $this->hasMany(Seguidores::className(), ['usuario_id' => 'id']);
    }

     /**
     * Gets query for [[Seguidores]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSeguidos()
    {
        return $this->hasMany(Seguidores::className(), ['seguidor_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPublicaciones()
    {
        return $this->hasMany(Publicaciones::className(), ['usuario_id' => 'id']);
    }

    public static function estaBloqueado($id) {
        return Bloqueados::find()
        ->andwhere(['usuario_id' => $id])
        ->andWhere(['bloqueado_id' => Yii::$app->user->identity->id])
        ->exists();
    }

    public static function Bloqueado($id) {
        return Bloqueados::find()
        ->andwhere(['usuario_id' => Yii::$app->user->identity->id])
        ->andWhere(['bloqueado_id' => $id])
        ->exists();
    }
}
