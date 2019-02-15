<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\web\UploadedFile;

class UploadForm extends Model
{
    /**
     * @var UploadedFile
     */
    public $imageFile;
    public function rules()
    {
        return [
            [['imageFile'], 'file', 'skipOnEmpty' => false, 'extensions' => 'png, jpg'],
        ];
    }
    public function upload($noticia_id)
    {
        if ($this->validate()) {
            $fileName = Yii::getAlias('@uploads/' . $noticia_id . '.' . $this->imageFile->extension);
            $this->imageFile->saveAs($fileName);
            //$imagine = new \Imagine\Gd\Imagine();
            //$image = $imagine->open($fileName);
            //$image->resize(new \Imagine\Image\Box(400, 200))->save($fileName);
            return true;
        }
        return false;
    }
}
