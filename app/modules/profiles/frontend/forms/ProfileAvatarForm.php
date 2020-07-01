<?php

namespace modules\profiles\frontend\forms;

use modules\profiles\common\models\Profile;
use ms\files\attachments\common\utils\FileUtil;
use Yii;
use yii\base\Model;

class ProfileAvatarForm extends Model
{
    public $image;

    public $profile_id;

    /** @var Profile */
    protected $profile = null;

    public function rules()
    {
        return [
            [['profile_id', 'image'], 'required'],
            ['profile_id', 'integer'],
            ['image', 'string'],
            ['profile_id', 'findProfile'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'image' => 'Изображение в Base64-строке',
            'id' => 'ID участника'
        ];
    }

    public function findProfile()
    {
        if (empty($this->profile_id) || null === ($profile = Profile::findOne($this->profile_id))) {
            $this->addError('profile_id', 'Доступ запрещен');
            return;
        }
        $this->profile = $profile;
    }

    public function process()
    {
        if (!$this->validate()) {
            return false;
        }

        if ($fileName = FileUtil::upload($this->image, "profile_{$this->profile_id}_avatar", Profile::AVATAR_DIR, true)) {
            $this->profile->avatar = $fileName;
            $this->profile->save(false);
            return true;
        }
        else {
            $this->addError('image', 'Изображение в неправильном формате');
            return false;
        }
    }

    /**
     * @return Profile
     */
    public function getProfile()
    {
        $this->profile->refresh();

        return $this->profile;
    }
}