<?php

namespace modules\profiles\frontend\models;

use ms\loyalty\taxes\common\models\Account;

class AccountProfileForm extends \ms\loyalty\taxes\common\forms\AccountProfileForm
{
    /**
     * @param Account $account
     * @return AccountProfileForm
     */
    public static function instantiateForAccount(Account $account)
    {
        if ($account->hasActiveAccountProfile() == false) {
            return new AccountProfileForm([
                'account' => $account,
            ]);
        }

        $activeAccount = $account->activeAccountProfile;

        $accountProfileForm = AccountProfileForm::findOne($activeAccount->id);
        $accountProfileForm->account = $account;

        return $accountProfileForm;
    }
}