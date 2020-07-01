<?php

namespace modules\actions\common\managers;

use modules\actions\common\models\Action;
use modules\profiles\common\models\Profile;

class Validator
{
    /**
     * @var Action
     */
    private $_action;

    /**
     * @param Action $action
     */
    public function __construct(Action $action)
    {
        $this->_action = $action;
    }

    /**
     * @param array $elements
     * @param string $value
     * @param string $attribute
     * @return bool
     */
    private function inArray(array $elements, string $value, string $attribute = 'id')
    {
        $values = array_column($elements, $attribute);

        return in_array($value, $values);
    }

    /**
     * @param Profile $profile
     * @return bool
     */
    public function validate(Profile $profile)
    {
        if (false === $this->validateRegion($profile)) {
            return false;
        };

        if (false === $this->validateCity($profile)) {
            return false;
        };

        if (false === $this->validateDealer($profile)) {
            return false;
        };

        if (false === $this->validateProfile($profile)) {
            return false;
        };

        if (false === $this->validateParticipant($profile)) {
            return false;
        };

        return true;
    }

    /**
     * @param Profile $profile
     * @return bool
     */
    public function validateRegion(Profile $profile)
    {
        $regions = $this->_action->regions;

        if (empty($regions)) {
            return true;
        }

        return $this->inArray($regions, $profile->city->region_id);
    }

    /**
     * @param Profile $profile
     * @return bool
     */
    public function validateCity(Profile $profile)
    {
        $cities = $this->_action->cities;

        if (empty($cities)) {
            return true;
        }

        return $this->inArray($cities, $profile->city_id);
    }

    /**
     * @param Profile $profile
     * @return bool
     */
    public function validateDealer(Profile $profile)
    {
        $dealers = $this->_action->dealers;

        if (empty($dealers)) {
            return true;
        }

        return $this->inArray($dealers, $profile->dealer_id);
    }

    /**
     * @param Profile $profile
     * @return bool
     */
    public function validateProfile(Profile $profile)
    {
        $profiles = $this->_action->profiles;

        if (empty($profiles)) {
            return true;
        }

        return $this->inArray($profiles, $profile->id);
    }

    /**
     * @param Profile $profile
     * @return bool
     */
    public function validateParticipant(Profile $profile)
    {
        $confirm_period = $this->_action->confirm_period;
        $start_action = $this->_action->start_on;

        $is_confirmed = $this->_action->getIs_confirmed($profile->id);
        $now = date_create_immutable('now')->setTime(0, 0, 0)->format('Y-m-d');

// участие подтверждено
        if ($is_confirmed) {
            return true;
        }
// Период не установлен
        if (empty($confirm_period)) {
            return true;
        }
// Время регистрации истекло
        $actual_action_to= date_create_immutable($start_action)->add(new \DateInterval("P".$confirm_period."D"))->format('Y-m-d');
        if ($actual_action_to<$now) {
            return false;
        }
        return true;
    }

}