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
            [['imagen'], 'file', 'skipOnEmpty' => false, 'extensions' => 'jpg'],
        ];
    }
    
    public function subida($id)
    {
        if ($this->validate()) {
            $filename = $id . '.' . $this->imagen->extension;
            $origen = Yii::getAlias('@uploads/' . $filename);
            $destino = Yii::getAlias('@img/' . $filename);
            $this->imagen->saveAs($origen);
            
            rename($origen, $destino);
            return true;
        } else {
            return false;
        }
    }

    public function subidaAws($id)
    {
        $filename = $id . '.' . $this->imagen->extension;
        $destino = Yii::getAlias('@img/' . $filename);
        $aws = Yii::$app->awssdk->getAwsSdk();
        $s3 = $aws->createS3();
        $amazon = $filename;
        $bucket = 'go00';
        $existe = $s3->doesObjectExist('go00', $amazon);
        if ($existe) :
            $s3->deleteObject([
                'Bucket'       => $bucket,
                'Key'          => $amazon,
            ]);
            $s3->putObject([
                'Bucket'       => $bucket,
                'Key'          => $amazon,
                'SourceFile'   => $destino,
                'ACL'          => 'public-read',
                'StorageClass' => 'REDUCED_REDUNDANCY',
                'Metadata'     => [
                    'param1' => 'value 1',
                    'param2' => 'value 2'
                ]
            ]);
        else :
            $s3->putObject([
                    'Bucket'       => $bucket,
                    'Key'          => $amazon,
                    'SourceFile'   => $destino,
                    'ACL'          => 'public-read',
                    'StorageClass' => 'REDUCED_REDUNDANCY',
                    'Metadata'     => [
                        'param1' => 'value 1',
                        'param2' => 'value 2'
                    ]
            ]);
        endif;

        return true;
    }

    public function borradoLocal($id)
    {
        unlink(Yii::getAlias('@img/' . $id . '.' . $this->imagen->extension));
    }

    public function descarga($key)
    {
        $aws = Yii::$app->awssdk->getAwsSdk();
        $s3 = $aws->createS3();
        $file = $s3->getObject([
            'Bucket' => 'go00',
            'Key' => $key,
        ]);
        return $file;
    }
}
