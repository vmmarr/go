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
 * @property int|null $direccion_id
 * @property string|null $descripcion
 * @property string $created_at
 * @property string|null $extension
 *
 * @property Comentarios[] $comentarios
 * @property Likes[] $likes
 * @property Direcciones $direccion
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
            [['usuario_id', 'direccion_id'], 'integer'],
            [['descripcion', 'extension'], 'string', 'max' => 255],
            [['direccion_id'], 'exist', 'skipOnError' => true, 'targetClass' => Direcciones::class, 'targetAttribute' => ['direccion_id' => 'id']],
            [['usuario_id'], 'exist', 'skipOnError' => true, 'targetClass' => Usuarios::class, 'targetAttribute' => ['usuario_id' => 'id']],
            // [['fecha'], 'validarFecha'],
            [['imagen'],
            'file', 
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
            'direccion_id' => 'Direccion ID',
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

    /**
     * Gets query for [[Comentarios]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUltimosComentarios()
    {
        return Comentarios::find()->where(['publicacion_id' => $this->id])->orderBy(['created_at' => SORT_DESC])->limit(2);
    }

    /**
     * Gets query for [[Comentarios]].
     *
     * @return \yii\db\ActiveQuery
     */
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

     /**
     * Gets query for [[Likes]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getLikes()
    {
        if ($this->getTotalLikes() > 2) {
            return Likes::find()->where(['publicacion_id' => $this->id])->orderBy(['id' => SORT_DESC])->limit(3);
        } else if ($this->getTotalLikes() > 0){
            return Likes::find()->where(['publicacion_id' => $this->id])->orderBy(['id' => SORT_DESC])->all();
        } else {
            return Likes::find()->where(['publicacion_id' => $this->id])->all();
        }
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
     * Gets query for [[Direccion]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDireccion()
    {
        return $this->hasOne(Direcciones::className(), ['id' => 'direccion_id']);
    }

     /**
     * Devuelve el usuario del comentario
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUsuarioComentario($id)
    {
        return Usuarios::find()->where(['id' => $id])->one();
    }

    /**
     * Devuelve el usuario del like
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUsuarioLike($id)
    {
        return Usuarios::find()->where(['id' => $id])->one();
    }

    public static function publicacionLike($id) {
        return Publicaciones::find()->where(['usuario_id' => $id])->all(); 
    }

    /**
     * Devuelve la imagen
     *
     * @return object
     */
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

    /**
     * Devuelve la url imagen
     *
     * @return object
     */
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

    /**
     * Guarda archivo en local
     *
     * @param integer $id
     * @return boolean
     */
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
            rename($origen, $destino);
            return true;
        } else {
            return false;
        }
    }
    
    /**
     * Guarda archivo en AWS
     *
     * @param integer $id
     * @return boolean
     */
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

    /**
     * Borra archivo en local
     *
     * @param integer $id
     * @return void
     */
    public function borradoLocal($id)
    {
        $carpeta = Yii::getAlias('@img/' . $id);
        foreach (glob($carpeta . '/*') as $archivos_carpeta) :
            if (is_dir($archivos_carpeta)) :
                $this->borradoLocal($archivos_carpeta);
            else :
                unlink($archivos_carpeta);
            endif;
        endforeach;
        rmdir($carpeta);
    }

    /**
     * Borra archivo en AWS
     *
     * @param integer $id
     * @return void
     */
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

    /**
     * Descarga un archivo del servidor
     *
     * @param integer $id
     * @return string
     */
    public function descarga($fichero, $id)
    {
        $f = $this->enlace($fichero);
        
        $downloadedFileContents = file_get_contents($f);
        if (!file_exists(Yii::getAlias('@img'))) {
            mkdir(Yii::getAlias('@img'));
        }

        $carpeta = Yii::getAlias('@img/' . $id);
        
        if (!file_exists($carpeta)) {
            mkdir($carpeta);
        }
        $fileName = Yii::getAlias('@img' . $this->usuario_id . '/' . $fichero);
        
        file_put_contents($fileName, $downloadedFileContents);

        return $fileName;
    }

    /**
     * Devuelve el enlace de un archivo del servidor
     *
     * @param integer $id
     * @return string
     */
    public static function enlace($fichero) {
        $aws = Yii::$app->awssdk->getAwsSdk();
        $s3 = $aws->createS3();
        $file = $s3->getObjectUrl('go00', $fichero);
        return $file;
    }
}
