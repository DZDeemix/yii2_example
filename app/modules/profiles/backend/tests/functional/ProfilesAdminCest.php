<?php

namespace modules\profiles\backend\tests\functional;

use modules\profiles\backend\tests\FunctionalTester;
use ms\loyalty\api\common\fixtures\ProfileFixture;
use ms\loyalty\api\common\fixtures\PurseFixture;
use Yii;
use yz\admin\fixtures\AdminUserFixture;

class ProfilesAdminCest
{
    /**
     * Load fixtures before db transaction begin
     * Called in _before()
     *
     * @see \Codeception\Module\Yii2::_before()
     * @see \Codeception\Module\Yii2::loadFixtures()
     * @return array
     */
    public function _fixtures()
    {
        return [
            'admins' => [
                'class' => AdminUserFixture::class,
                'dataFile' => codecept_data_dir() . 'admins.php'
            ],
            'profiles' => [
                'class' => ProfileFixture::class,
                'dataFile' => codecept_data_dir() . 'profiles.php'
            ],
            'purses' => [
                'class' => PurseFixture::class,
                'dataFile' => codecept_data_dir() . 'purses.php'
            ],
        ];
    }

    public function checkProfilesList(FunctionalTester $I)
    {
        $this->checkPage('/profiles/profiles/index', $I);
    }

    public function checkProfilesFilter(FunctionalTester $I)
    {
        $this->checkPage('/profiles/profiles/index?' . urlencode('ProfileSearch[full_name]=Иванов'), $I);
    }

    public function checkProfilesUpdate(FunctionalTester $I)
    {
        $I->amLoggedInAs(AdminUserFixture::getSuperadmin());
        $I->amOnRoute('/profiles/profiles/update?id=1');

        $I->seeElement('button[value="save_and_leave"]');
        $I->click('button[value="save_and_leave"]');

        $I->seeElement('div.grid-view');
        $I->dontSeeElement('button[value="save_and_leave"]');
    }

    public function checkProfilesCreate(FunctionalTester $I)
    {
        $I->amLoggedInAs(AdminUserFixture::getSuperadmin());
        $I->amOnRoute('/profiles/profiles/create');

        $I->seeElement('button[value="save_and_leave"]');
        $I->fillField('#profile-phone_mobile_local', '+7 929 900-40-99');
        $I->fillField('#profile-last_name', 'Волков');
        $I->fillField('#profile-first_name', 'Петр');
        $I->click('button[value="save_and_leave"]');

        $I->seeElement('div.grid-view');
        $I->dontSeeElement('button[value="save_and_leave"]');
    }

    public function checkProfilesImport(FunctionalTester $I)
    {
        $this->checkPage('/profiles/import-profiles/index', $I, 'div.batch-import');
    }

    public function checkProfileTransactionsList(FunctionalTester $I)
    {
        $this->checkPage('/profiles/profile-transactions/index', $I);
    }

    public function checkProfileTransactionsFilter(FunctionalTester $I)
    {
        $this->checkPage('/profiles/profile-transactions/index?'
            . urlencode('ProfileTransactionSearch[profile__full_name]=Иванов'), $I);
    }

    private function checkPage($route, FunctionalTester $I, $html = 'div.grid-view')
    {
        $I->amLoggedInAs(AdminUserFixture::getSuperadmin());
        $I->amOnRoute($route);
        $I->seeElement($html);
    }
}
