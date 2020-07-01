<?php

namespace modules\sales\common\models;

/**
 * Created by PhpStorm.
 * User: MihailKri
 * Date: 24.04.2018
 * Time: 15:12
 */
use Yii;
use yii\base\Model;
use yii\helpers\FileHelper;
use yii\web\UploadedFile;

class UploadSaleForm extends Model
{
    /** @var \yii\web\UploadedFile */
    public $imageFile;

    public function rules()
    {
        return [
            [
                'imageFile',
                'file',
                'skipOnEmpty' => false,
                'extensions' => 'png, jpg, gif, jpeg, pdf',
                'maxSize' => 20 * 1024 * 1012,
                'tooBig' => 'Превышен лимит загрузки файла'
            ],
        ];
    }

    public function upload()
    {
        if ($this->validate()) {
            $file = uniqid() . '.' . $this->imageFile->extension;
            $dir = Yii::getAlias("@data/sales");
            FileHelper::createDirectory($dir);

            $this->imageFile->saveAs($dir . DIRECTORY_SEPARATOR . $file);

            if (($_ENV['YII_ENV'] ?? null) == 'dev') {
                $frontendDir = Yii::getAlias("@frontendWebroot/data/sales");
                FileHelper::copyDirectory($dir, $frontendDir);
            }

            return $file;
        }
        else {
            $this->addError('imageFile', 'Неверно указан формат файла. Допустимые форматы: png, jpg, gif, jpeg, pdf. Максимальный размер файла: 20 МБ');
            return null;
        }
    }
}
