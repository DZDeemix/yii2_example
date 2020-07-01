<?php

namespace modules\profiles\common\managers;

use modules\profiles\common\models\Profile;

/**
 * @property Profile $profile
 */
class RoleManager
{
    const ROLE_PROFCLUB = 'dealer';
    const ROLE_REGULAR = 'rtt';

    /**
     * @var Profile
     */
    private $profile;
    /**
     * @var string
     */
    private $_errorMessage;

    function __construct(Profile $profile)
    {
        $this->profile = $profile;
    }

    public static function getList()
    {
        return [
            self::ROLE_PROFCLUB => 'Закупщик',
            self::ROLE_REGULAR => 'Продавец',
        ];
    }

    /**
     * @return string
     */
    public function getErrorMessage()
    {
        return $this->_errorMessage;
    }

    /**
     * @return bool
     */
    public function isRegular()
    {
        return $this->profile->role === self::ROLE_REGULAR;
    }

    /**
     * @return bool
     */
    public function isProfclub()
    {
        return $this->profile->role === self::ROLE_PROFCLUB;
    }

    /**
     * @return bool
     */
    public function isChecked()
    {
        $result = $this->profile->checked_at !== null;

        if ($result === false) {
            $this->_errorMessage = "Ваша учетная запись не подтверждена администратором.";
        }

        return $result;
    }

    /**
     * @return bool
     */
    public function isBlocked()
    {
        $result = $this->profile->blocked_at !== null;

        if ($result === false) {
            $this->_errorMessage = "Ваша учетная запись заблокирована администратором.";
        }

        return $result;
    }

    /**
     * @return bool
     */
    public function canSpendBonuses()
    {
        if ($this->isBlocked() === true) {
            return false;
        }

        if ($this->isChecked() === false) {
            return false;
        }

        // TODO: add isBaned

        return true;
    }

    /**
     * @return bool
     * @throws \Exception
     */
    public function canCreateSale()
    {
        if ($this->isBlocked() === true) {
            return false;
        }

        if ($this->isChecked() === false) {
            return false;
        }

        // TODO: add isBaned

        return true;
    }

}