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
            [['activate'], 'number'],
            [['nombre', 'email', 'password', 'authkey'], 'string', 'max' => 255],
            [['username'], 'string', 'max' => 60],
            [['username'], 'unique'],
            [
                ['password_repeat'],
                'required',
                'on' => self::SCENARIO_CREAR
            ],
            [
                ['password_repeat'],
                'compare',
                'compareAttribute' => 'password',
                'skipOnEmpty' => false,
                'on' => [self::SCENARIO_CREAR, self::SCENARIO_UPDATE],
            ],
            [['biografia'] => 'string', 'max' => 255],
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
            'activate' => 'Activate',
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

    public function beforeSave($insert)
    {
        if (!parent::beforeSave($insert)) {
            return false;
        }

        if ($insert) {
            if ($this->scenario === self::SCENARIO_CREAR) {
                $security = Yii::$app->security;
                $this->authkey = $security->generateRandomString();
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
}
