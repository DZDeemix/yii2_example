<?php

namespace modules\profiles\common;

/**
 * Class Module
 */
class Module extends \yz\Module
{
    const PROFILE_TYPE_CITY_REGION = 'city_region';
    const PROFILE_TYPE_REGION = 'region';
    const PROFILE_TYPE_DEALER_CITY_REGION = 'dealer_city_region';
    const PROFILE_TYPE_DEALER_REGION = 'dealer_region';

    /**
     * If true, it will be possible to register new user not from the list
     *
     * @var bool
     */
    public $enableFreeRegistration = false;

    public $profileType = self::PROFILE_TYPE_CITY_REGION;
}