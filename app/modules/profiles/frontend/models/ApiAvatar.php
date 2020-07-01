<?php

namespace modules\profiles\frontend\models;

use modules\profiles\common\models\Profile;
use Yii;
use yii\base\Model;
use yii\helpers\FileHelper;

class ApiAvatar extends Model
{
    public $type;
    public $image;
    public $id;
    protected $filename;

    public function rules()
    {
        return [
            ['type', 'string'],
            ['type', 'required'],
            ['image', 'string'],
            ['image', 'required'],
            ['id', 'string'],
            ['id', 'required'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'type' => 'Расширение картинки',
            'image' => 'Изображение в Base64',
            'id' => 'ID участника'
        ];
    }

    public function save()
    {
        /** @var Profile $profile */
        $profile = Profile::findOne(Yii::$app->request->post('id'));
        if ($profile == null) {
            $this->addError('image', 'Ошибка: участник не найден');
            return false;
        }

        $fileName = uniqid() . '.' . $this->type;
        $dir = Yii::getAlias('@data/avaimages');
        FileHelper::createDirectory($dir);
        $filePath = Yii::getAlias($dir . DIRECTORY_SEPARATOR . $fileName);
        $fileSize = (int) file_put_contents($filePath, base64_decode($this->image));

        if ($fileSize == 0) {
            $this->addError('image', 'Ошибка при загрузке файла, неверное значение');

            return false;
        }

        if (($_ENV['YII_ENV'] ?? null) == 'dev') {
            FileHelper::copyDirectory($dir, Yii::getAlias("@frontendWebroot/data/avaimages"));
        }

        $this->filename = $fileName;
        $profile->avatar = $fileName;
        $profile->save(false);

        return true;
    }

    public function getWebpath()
    {
        return Yii::getAlias('@frontendWeb/data/avaimages/' . $this->filename);
    }

    public function getFilename()
    {
        return $this->filename;
    }
}