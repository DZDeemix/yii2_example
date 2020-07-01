<?php

namespace modules\profiles\common\managers;

use modules\profiles\common\models\LeaderAdminRegion;
use yii\db\ActiveQuery;
use modules\profiles\backend\rbac\Rbac;
use modules\profiles\common\models\Leader;
use modules\profiles\common\models\Profile;
use modules\profiles\common\models\City;

/**
 * @property Leader $leader
 */
class LeaderRoleManager
{
    /**
     * @var Leader
     */
    private $_leader;

    function __construct(Leader $leader)
    {
        $this->_leader = $leader;
    }

    /**
     * @return bool
     */
    public function isAdminRegional()
    {
        return false;// $this->_leader->role === Rbac::ROLE_ADMIN_REGION;
    }

    /**
     * @return bool
     */
    public function isAdminLegalPerson()
    {
        return  $this->_leader->role === Rbac::ROLE_ADMIN_LEGAL;
    }

    /**
     * @return bool
     */
    public function isAdminMainLegalPerson()
    {
        return $this->_leader->role === Rbac::ADMIN_ESTIMA;
    }

    /**
     * @return bool
     */
    public function isAdminOp()
    {
        return  false;//$this->_leader->role === Rbac::ROLE_ADMIN_CURATOR;
    }

    public function isAdmin()
    {
        return false; //$this->_leader->role === Rbac::ROLE_ADMIN_JUNIOR;
    }

    /**
     * @return bool
     */
    public function canBlockProfile()
    {
        return false;
    }

    /**
     * @return bool
     */
    public function canEditSale()
    {
        return false;
    }

    /**
     * @return bool
     */
    public function canUpdateSaleStatus()
    {
        return false;
    }

    /**
     * @return bool
     */
    public function canDeleteSale()
    {
        return false;
    }

    /**
     * Returns leader Cities ids by region link
     * @return array
     */
    public function getCitiesIds()
    {
        $query = City::find()
            ->joinWith(['region'], 'false')
            ->where([
                '{{%regions}}.id' => Leader::getLeaderRegion()
            ])
            ->groupBy('{{%cities}}.id');

        return $query->column();
    }

    /**
     * Returns leader Dealers ids by region link
     * @return array
     */
    public function getDealerIds()
    {
        $query = Profile::find()
            ->select('dealer_id')
            ->joinWith(['city' => function (ActiveQuery $query) {
                $query->joinWith(['region']);
            }], false)
            ->where([
                '{{%regions}}.id' => $this->_leader->region_id
            ])
            ->distinct();

        return $query->column();
    }

    /**
     * Returns leader Profile ids by region link
     * @return array
     */
    public function getProfileIds()
    {
        $query = Profile::find()
            ->joinWith(['city' => function (ActiveQuery $query) {
                $query->joinWith(['region']);
            }], false)
            ->where([
                '{{%regions}}.id' => $this->_leader->region_id
            ]);

        return $query->column();
    }

    public function getRegionsForRoleAdmin()
    {
        return LeaderAdminRegion::find()->select('id')->where(['leader_id' => \Yii::$app->request->get('id')])->column();
    }


}