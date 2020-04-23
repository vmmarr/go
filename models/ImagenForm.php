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
            // if (file_exists($destino)) :
            //     unlink($destino);
            // endif;
            rename($origen, $destino);
            return true;
        } else {
            return false;
        }
    }

    public function subidaAws($id)
    {
        $iduser = Yii::$app->user->id;
        $filename = $id . '.' . $this->imagen->extension;
        $destino = Yii::getAlias('@img/' . $iduser  . '/' . $filename);
        $aws = Yii::$app->awssdk->getAwsSdk();
        $s3 = $aws->createS3();
        $amazon = $iduser . '/' . $filename;
        $s3->putObject([
                'Bucket'       => 'go00',
                'Key'          => $amazon,
                'SourceFile'   => $destino,
                'ACL'          => 'public-read',
                'StorageClass' => 'REDUCED_REDUNDANCY',
                'Metadata'     => [
                    'param1' => 'value 1',
                    'param2' => 'value 2'
                ]
        ]);

        return true;
    }

    public function borradoLocal($id)
    {
        unlink($id . '.png');
    }

    public function descarga($key)
    {
        $aws = Yii::$app->awssdk->getAwsSdk();
        $s3 = $aws->createS3();
        //get the last object from s3
        //$object = end($result['Contents']);1
        // $key = $object['Key'];
        $file = $s3->getObject([
            'Bucket' => 'go00',
            'Key' => $key,
        ]);
        return $file;
        // save it to disk
    }
}
