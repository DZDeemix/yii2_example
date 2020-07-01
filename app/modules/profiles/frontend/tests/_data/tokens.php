<?php

return [
    [
        'id' => '1',
        'login' => \ms\loyalty\api\common\fixtures\ProfileFixture::REGISTERED_PHONE,
        'code' => \ms\loyalty\api\common\fixtures\TokenFixture::CODE_SMS_PROFILE,
        'token' => \ms\loyalty\api\common\fixtures\TokenFixture::TOKEN_SMS_PROFILE,
        'type' => \ms\loyalty\api\common\models\Token::TYPE_SMS_PROFILE,
        'created_at' => '2018-11-09 00:00:00',
        'updated_at' => '2018-11-09 00:00:00',
    ],
];
