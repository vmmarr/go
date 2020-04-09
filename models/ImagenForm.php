<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\imagine\Image;

class ImagenForm extends Model
{
    public $imagen;

    public function rules()
    {
        return [
            [['imagen'], 'file', 'skipOnEmpty' => false, 'extensions' => 'png, jpg'],
        ];
    }
    
    public function subida($id)
    {
        if ($this->validate()) {
            $filename = $id . '.' . $this->imagen->extension;
            $origen = Yii::getAlias('@uploads/' . $filename);
            $destino = Yii::getAlias('@img/' . $filename);
            $this->imagen->saveAs($origen);
            if (file_exists($destino)) :
                unlink($destino);
            endif;
            rename($origen, $destino);
            return true;
        } else {
            return false;
        }
    }
}
