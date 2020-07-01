<?php

namespace modules\actions\common\models;

use yii\db\ActiveQuery;

/**
 * Class ActionQuery
 */
class ActionQuery extends ActiveQuery
{
    /**
     * @param string $now
     * @return ActionQuery
     */
    public function runningNow(string $now = 'now')
    {
        $now = date_create_immutable($now)->setTime(0, 0, 0)->format('Y-m-d');
        $this->andWhere([
            'AND',
            ['<=', '{{%actions}}.start_on', $now],
            ['>=', '{{%actions}}.end_on', $now],
        ]);
        return $this;
    }

    /**
     * @param string $now
     * @return ActionQuery
     */
    public function finished(string $now = 'now')
    {
        $now = date_create_immutable($now)->setTime(0, 0, 0)->format('Y-m-d');

        $this->andWhere(['<', '{{%actions}}.end_on', $now]);

        return $this;
    }

    /**
     * @param integer $id
     * @return ActionQuery
     */
    public function byId(int $id)
    {
        $this->andWhere(['{{%actions}}.id' => $id]);

        return $this;
    }

    /**
     * @param string $role
     * @return ActionQuery
     */
    public function byRole(string $role)
    {
        $this->andWhere(['{{%actions}}.role' => $role]);

        return $this;
    }

    /**
     * @return ActionQuery
     */
    public function active()
    {
        $this->andWhere(['status' => Action::STATUS_ACTIVE]);

        return $this;
    }

    /**
     * @param string $role
     * @return ActionQuery
     */
    public function actual(string $role)
    {
        $this->joinWith([
            'regions',
            'cities',
            'dealers',
            'profiles',
        ])
            ->runningNow()
            ->active()
            ->byRole($role)
            ->orderBy(['created_at' => SORT_DESC]);

        return $this;
    }

    public function confirmed(string $profile_id)
    {
        return $this;
    }

}