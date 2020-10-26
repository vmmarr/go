<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "bloqueados".
 *
 * @property int $id
 * @property int $usuario_id
 * @property int $bloqueado_id
 *
 * @property Usuarios $usuario
 * @property Usuarios $bloqueado
 */
class Bloqueados extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'bloqueados';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['usuario_id', 'bloqueado_id'], 'required'],
            [['usuario_id', 'bloqueado_id'], 'default', 'value' => null],
            [['usuario_id', 'bloqueado_id'], 'integer'],
            [['usuario_id'], 'exist', 'skipOnError' => true, 'targetClass' => Usuarios::className(), 'targetAttribute' => ['usuario_id' => 'id']],
            [['bloqueado_id'], 'exist', 'skipOnError' => true, 'targetClass' => Usuarios::className(), 'targetAttribute' => ['bloqueado_id' => 'id']],
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
            'bloqueado_id' => 'Bloqueado ID',
        ];
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

    /**
     * Gets query for [[Bloqueado]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getBloqueado()
    {
        return $this->hasOne(Usuarios::className(), ['id' => 'bloqueado_id']);
    }
}
