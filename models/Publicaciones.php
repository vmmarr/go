<?php

namespace app\models;

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
            [['created_at', 'update_at'], 'safe'],
            [['created_at', 'update_at'], 'required'],
            [['descripcion'], 'string', 'max' => 255],
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
            'update_at' => 'Update At',
        ];
    }

    /**
     * Gets query for [[Comentarios]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getComentarios()
    {
        return $this->hasMany(Comentarios::className(), ['publicacion_id' => 'id']);
    }

    /**
     * Gets query for [[Likes]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getLikes()
    {
        return $this->hasMany(Likes::className(), ['publicacion_id' => 'id']);
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

    public function getImagen()
    {
        if ($this->_imagen !== null) {
            return $this->_imagen;
        }

        // $this->setImagen(Yii::getAlias('@img/' . $this->id . '.png'));
        $this->setImagen(Yii::getAlias('@img/' . $this->id . '.png'));
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

        $this->setImagenUrl(Yii::getAlias('@imgUrl/' . $this->id . '.png'));
        return $this->_imagenUrl;
    }

    public function setImagenUrl($imagenUrl)
    {
        $this->_imagenUrl = $imagenUrl;
    }
}
