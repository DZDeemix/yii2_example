<?php

namespace modules\profiles\common\widgets;

use marketingsolutions\finance\models\Transaction;
use modules\profiles\common\models\Profile;
use yii\base\Widget;

class ProfilePurseWidget extends Widget
{
    /** @var Profile */
    public $profile;

    public function run()
    {
        /** @var Transaction[] $transactions */
        $transactions = Transaction::find()
            ->where(['purse_id' => $this->profile->purse->id])
            ->orderBy(['created_at' => SORT_DESC])
            ->all();

        return $this->render('profile-purse', [
            'profile' => $this->profile,
            'transactions' => $transactions,
        ]);
    }
}