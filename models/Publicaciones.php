<?php

namespace app\models;

use PhpParser\Node\Expr\New_;
use Yii;
use yii\imagine\Image;

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
            [['descripcion'], 'string', 'max' => 255],
            [['extension'], 'string', 'max' => 255],
            [['usuario_id'], 'exist', 'skipOnError' => true, 'targetClass' => Usuarios::className(), 'targetAttribute' => ['usuario_id' => 'id']],
            [['imagen'],
            'file', 
            //'extensions' => 'jpg, png, mp4',
            'maxSize' => 8000000,
            'skipOnEmpty' => false,
            ]
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
            'extension' => 'Extension',
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

    public function getUltimosComentarios()
    {
        return Comentarios::find()->where(['publicacion_id' => $this->id])->orderBy(['created_at' => SORT_DESC])->limit(2);
    }

    public function getComentarios()
    {
        return Comentarios::find()->where(['publicacion_id' => $this->id])->orderBy(['created_at' => SORT_DESC]);
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

    public static function publicacionLike($id) {
        return Publicaciones::find()->where(['usuario_id' => $id])->all(); 
    }

    public function getImagen()
    {
        if ($this->_imagen !== null) {
            return $this->_imagen;
        }

        $this->setImagen($this->usuario_id . '/' . $this->id . '.' . $this->extension);
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

        $this->setImagenUrl($this->usuario_id . '/' . $this->id . '.' . $this->extension);
        return $this->_imagenUrl;
    }

    public function setImagenUrl($imagenUrl)
    {
        $this->_imagenUrl = $imagenUrl;
    }

    public function subida($id)
    {
        if (!file_exists(Yii::getAlias('@img'))) {
            mkdir(Yii::getAlias('@img'));
        }

        if (!file_exists(Yii::getAlias('@uploads'))) {
            mkdir(Yii::getAlias('@uploads'));
        }
        $iduser = Yii::$app->user->id;
        $carpeta = Yii::getAlias('@img/' . $iduser);
        
        if (!file_exists($carpeta)) {
            mkdir($carpeta);
        }
        
        if ($this->validate()) {
            $filename = $id . '.' . $this->imagen->extension;
            $origen = Yii::getAlias('@uploads/' . $filename);
            $destino = Yii::getAlias('@img/' . $iduser  . '/' . $filename);
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
        if($this->imagen->extension !== 'mp4') {
            Image::resize($destino, 500, 500, false, true)
            ->save($destino);
        }
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

    public function borradoLocal()
    {
        $carpeta = Yii::getAlias('@img/' . Yii::$app->user->id);
        foreach (glob($carpeta . '/*') as $archivos_carpeta) :
            if (is_dir($archivos_carpeta)) :
                $this->borradoLocal($archivos_carpeta);
            else :
                unlink($archivos_carpeta);
            endif;
        endforeach;
        rmdir($carpeta);
    }

    public function borradoAmazon($id)
    {
        $fichero = Yii::$app->user->id . '/' . $id;
        $aws = Yii::$app->awssdk->getAwsSdk();
        $s3 = $aws->createS3();
        $s3->deleteObject([
            'Bucket'       => 'go00',
            'Key'          => $fichero,
        ]);
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

    public static function enlace($fichero) {
        $aws = Yii::$app->awssdk->getAwsSdk();
        $s3 = $aws->createS3();
        $file = $s3->getObjectUrl('go00', $fichero);
        return $file;
    }
}
