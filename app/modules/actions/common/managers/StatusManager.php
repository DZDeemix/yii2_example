<?php

namespace modules\actions\common\managers;

use modules\actions\common\models\Action;
use modules\actions\common\models\ActionProfileByDealer;
use modules\profiles\backend\rbac\Rbac;
use modules\profiles\common\models\Leader;
use modules\sales\common\models\Sale;
use yz\admin\models\User;

class StatusManager
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
     * @return bool
     */
    public function isActive()
    {
        return Action::STATUS_ACTIVE === $this->_action->status;
    }

    /**
     * @return bool
     */
    public function isFinished()
    {
        return Action::STATUS_FINISHED === $this->_action->status;
    }

    /**
     * @return bool
     */
    public function isChecked()
    {
        return Action::STATUS_OLAP_CHECKED === $this->_action->status;
    }

    /**
     * @return bool
     */
    public function isPaid()
    {
        return Action::STATUS_PAID === $this->_action->status;
    }

    /**
     * @return bool
     */
    public function hasSales()
    {
        return Sale::find()
            ->where(['action_id' => $this->_action->id])
            ->exists();
    }

    /**
     * @return bool
     */
    public function hasActionProfileByDealer()
    {
        return ActionProfileByDealer::find()
            ->where(['action_id' => $this->_action->id])
            ->exists();
    }

    /**
     * @return bool
     */
    public function isActionOwner()
    {
        $leader = Leader::getLeaderByIdentity();

        if (null === $leader) {
            return false;
        }

        if (false === $leader->roleManager->isAdminRegional()) {
            return false;
        }

        if ($leader->adminUser->id !== $this->_action->admin_id) {
            return false;
        }

        return true;
    }

    /**
     * @return bool
     */
    public function isActionOwnerSibling()
    {
        $leader = Leader::getLeaderByIdentity();

        if (null === $leader) {
            return false;
        }

        if (false === $leader->roleManager->isAdminRegional()) {
            return false;
        }

        $adminOwner = User::findOne(['id' => $this->_action->admin_id]);

        if (null === $adminOwner || $adminOwner->is_super_admin) {
            return false;
        }

       /* $leaderOwner = Leader::findOne(['identity_id' => $adminOwner->id]);

        if ($leader->region_id === $leaderOwner->region_id) {
            return true;
        }*/

        return true;
    }

    /**
     * @return bool
     */
    public function canChangeStatus()
    {
        if (Rbac::isAdmin()) {
            return true;
        }

        if (Rbac::isNewAdminRoles()) {
            return true;
        }

        if ($this->isActionOwner()) {
            return true;
        }

        if ($this->isActionOwnerSibling()) {
            return true;
        }

        return false;
    }

    /**
     * @return bool
     */
    public function canView()
    {
        if (Rbac::isAdmin()) {
            return true;
        }
        if (Rbac::getNewAdminRoles()) {
            return true;
        }

        if ($this->isActionOwner()) {
            return true;
        }

        if ($this->isActionOwnerSibling()) {
            return true;
        }

        return false;
    }

    /**
     * @return bool
     */
    public function canUpdate()
    {
        if ($this->_action->isNewRecord) {
            return true;
        }

        if ($this->isFinished()) {
            return false;
        }

        if ($this->hasSales()) {
            return false;
        }

        return true;
    }

    public function canEditAction()
    {
        if ($this->_action->isNewRecord) {
            return true;
        }

        if ($this->isFinished()) {
            return false;
        }

        if ($this->isChecked()) {
            return false;
        }

        if ($this->isPaid()) {
            return false;
        }

        if ($this->hasSales()) {
            return true;
        }

        return true;
    }

    /**
     * @return bool
     */
    public function canBeDeleted()
    {
        if ($this->isFinished()) {
            return false;
        }

        if ($this->hasSales()) {
            return false;
        }

        if ($this->hasActionProfileByDealer()) {
            return false;
        }

        if ($this->isActionOwner()) {
            return true;
        }

        if (Rbac::isAdmin()) {
            return true;
        }

        return false;
    }

    /**
     * @return bool
     */
    public function finish()
    {
        return (bool)$this->_action->updateAttributes(['status' => Action::STATUS_FINISHED]);
    }


}