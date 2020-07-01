<?php

namespace modules\profiles\frontend\tests\api;

use modules\profiles\frontend\tests\ApiTester;
use ms\loyalty\api\common\fixtures\AccountFixture;
use ms\loyalty\api\common\fixtures\JwtFixture;
use ms\loyalty\api\common\fixtures\ProfileFixture;
use yii\helpers\Json;

class ProfilesApiCest
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
            'accounts' => [
                'class' => AccountFixture::class,
                'dataFile' => codecept_data_dir() . 'accounts.php'
            ],
            'jwt' => [
                'class' => JwtFixture::class,
                'dataFile' => codecept_data_dir() . 'jwt.php'
            ],
            'profiles' => [
                'class' => ProfileFixture::class,
                'dataFile' => codecept_data_dir() . 'profiles.php'
            ],
            'purses' => [
                'class' => ProfileFixture::class,
                'dataFile' => codecept_data_dir() . 'purses.php'
            ],
        ];
    }

    public function checkGetProfile(ApiTester $I)
    {
        $account = AccountFixture::getAccount();
        $jwt = JwtFixture::getJwt();
        $url = ($_ENV['FRONTEND_WEB'] ?? null) . '/profiles/api/auth/get-profile';
        $data = [
            'profile_id' => ProfileFixture::REGISTERED_ID,
        ];

        $I->wantTo('Get profile info via API');
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->haveHttpHeader('X-Token', $account->access_token);
        $I->haveHttpHeader('Authorization', 'Bearer ' . $jwt->token);
        $I->sendPOST($url, Json::encode($data));

        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
        $I->seeResponseContains('"profile"');
    }

    /*
    public function checkLoginFail(ApiTester $I)
    {
        LoginTry::deleteAll();
        $account = AccountFixture::getAccount();
        $baseUrl = $_ENV['FRONTEND_WEB'] ?? null;
        $data = [
            'login' => ProfileFixture::REGISTERED_PHONE,
            'password' => '---'
        ];

        $I->wantTo('Login fails via API');
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->haveHttpHeader('X-Token', $account->access_token);
        $I->sendPOST($baseUrl . '/api/login', json_encode($data));

        $I->seeResponseCodeIs(403);
        $I->seeResponseIsJson();
        $I->seeResponseContains('"errors"');
    } */
}
