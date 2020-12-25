<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "direcciones".
 *
 * @property int $id
 * @property int|null $latitud
 * @property int|null $longitud
 * @property string|null $nombre
 *
 * @property Publicaciones[] $publicaciones
 */
class Direcciones extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'direcciones';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['latitud'],'validaLatitud'],
            [['longitud'],'validaLongitud'],
            [['latitud', 'longitud', 'nombre'], 'required'],
            [['latitud', 'longitud'], 'string', 'max' => 20],
            [['nombre'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'latitud' => 'Latitud',
            'longitud' => 'Longitud',
            'nombre' => 'Nombre',
        ];
    }

    /**
     * Gets query for [[Publicaciones]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPublicaciones()
    {
        return $this->hasMany(Publicaciones::className(), ['direccion_id' => 'id']);
    }

    public function validaLatitud() {
        if (preg_match('/^(-?)([\d]{1,2})(\.)(\d{1,7})$/', $this->latitud)) {
            list($entero, $decimal) = explode('.',$this->latitud);
            if($entero <= 90 && $entero >= -90){

            } else  {
                $this->addError('latitud',"Debe ser una latitud 90");
            }
        } else {
            $this->addError('latitud',"Debe ser una latitud ");
        }
    }

    public function validaLongitud()
    {
        if (preg_match('/^(-?)([\d]{1,3})(\.)(\d{1,7})$/', $this->longitud)) {
            list($entero, $decimal) = explode('.',$this->longitud);
            if($entero <= 180 && $entero >= -180){
//pasa
            } else  {
                $this->addError('longitud',"Debe ser una latitud 90");
            }
        } else {
            $this->addError('longitud',"Debe ser una latitud ");
        }
    }
}
