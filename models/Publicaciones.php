<?php

namespace app\models;

use PhpParser\Node\Expr\New_;
use Yii;

/**
 * This is the model class for table "publicaciones".
 *
 * @property int $id
 * @property int $usuario_id
 * @property string|null $descripcion
 * @property string $created_at
 * @property string $update_at
 *
 * @property Comentarios[] $comentarios
 * @property Likes[] $likes
 * @property Usuarios $usuario
 */
class Publicaciones extends \yii\db\ActiveRecord
{
    private $_imagen = null;
    private $_imagenUrl = null;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'publicaciones';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['usuario_id'], 'required'],
            [['usuario_id'], 'integer'],
            [['created_at'], 'safe'],
            [['created_at'], 'required'],
            [['descripcion'], 'string', 'max' => 255],
            [['usuario_id'], 'exist', 'skipOnError' => true, 'targetClass' => Usuarios::className(), 'targetAttribute' => ['usuario_id' => 'id']],

        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'usuario_id' => 'Usuario ID',
            'descripcion' => 'Descripcion',
            'created_at' => 'Created At',
        ];
    }

    /**
     * Gets query for [[Comentarios]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTotalComentarios()
    {
        $total = $this->hasMany(Comentarios::className(), ['publicacion_id' => 'id']);

        return $total->count();
    }

    public function getComentarios()
    {
        return Comentarios::find()->where(['publicacion_id' => $this->id])->orderBy('created_at')->all();
    }

    /**
     * Gets query for [[Likes]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTotalLikes()
    {
        $total = $this->hasMany(Likes::className(), ['publicacion_id' => 'id']);

        return $total->count();
    }

    public function getLikes()
    {
        return Likes::find()->where(['publicacion_id' => $this->id])->all();
    }

    /**
     * Gets query for [[Usuario]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUsuario()
    {
        return $this->hasOne(Usuarios::className(), ['id' => 'usuario_id']);
    }

    public function getUsuarioComentario($id)
    {
        return Usuarios::find()->where(['id' => $id])->one();
    }

    public function getUsuarioLike($id)
    {
        return Usuarios::find()->where(['id' => $id])->one();
    }

    public function getImagen()
    {
        if ($this->_imagen !== null) {
            return $this->_imagen;
        }

        $this->setImagen(Yii::getAlias($this->usuario_id . '/' . $this->id . '.jpg'));
        return $this->_imagen;
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

        $this->setImagenUrl(Yii::getAlias($this->usuario_id . '/' . $this->id . '.jpg'));
        return $this->_imagenUrl;
    }

    public function setImagenUrl($imagenUrl)
    {
        $this->_imagenUrl = $imagenUrl;
    }
}
