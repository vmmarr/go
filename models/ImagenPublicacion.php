<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\imagine\Image;

class ImagenPublicacion extends Model
{
    public $imagen;

    public function rules()
    {
        return [
            [['imagen'], 'file', 'skipOnEmpty' => false, 'extensions' => 'png, jpg'],
        ];
    }
    
    public function subida()
    {
        if ($this->validate()) {
            $id = Yii::$app->user->id;
            $carpeta = './img/' . $id;

            if (!file_exists($carpeta)) {
                mkdir($carpeta);
                
                $filename = '1.' . $this->imagen->extension;
                $origen = Yii::getAlias('@uploads/' . $filename);
                $destino = Yii::getAlias('@img/' . $id  . '/' . $filename);
                $this->imagen->saveAs($origen);
                // if (file_exists($destino)) :
                //     unlink($destino);
                // endif;
                rename($origen, $destino);
            } else {
                $total_imagenes = count(glob($carpeta . '/{*}', GLOB_BRACE));
                
                $filename = ($total_imagenes + 1) . '.' . $this->imagen->extension;
                $origen = Yii::getAlias('@uploads/' . $filename);
                $destino = Yii::getAlias('@img/' . $id  . '/' . $filename);
                $this->imagen->saveAs($origen);
                rename($origen, $destino);
            }
            return true;
        } else {
            return false;
        }
    }
}