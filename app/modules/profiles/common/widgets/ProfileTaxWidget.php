<?php

namespace modules\profiles\common\widgets;

use modules\profiles\common\models\Profile;
use ms\files\attachments\common\models\AttachedFile;
use ms\loyalty\taxes\common\forms\AccountProfileForm;
use ms\loyalty\taxes\common\models\Account;
use ms\loyalty\taxes\common\models\AccountProfile;
use Yii;
use yii\base\Widget;

class ProfileTaxWidget extends Widget
{
    /** @var Profile */
    public $profile;

    public function run()
    {
        $accountProfile = $this->findAccountProfile();

        return $this->render('profile-tax', [
            'model' => $accountProfile,
        ]);
    }

    /**
     * @return AccountProfile|null
     */
    private function findAccountProfile()
    {
        if ($account = Account::findOne(['profile_id' => $this->profile->id])) {
            if ($accountProfile = AccountProfile::findOne(['account_id' => $account->id])) {
                return $accountProfile;
            }
        }

        return null;
    }
}