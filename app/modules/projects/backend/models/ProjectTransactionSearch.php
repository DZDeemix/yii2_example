<?php

namespace modules\projects\backend\models;

use marketingsolutions\finance\models\Purse;
use modules\profiles\common\models\Leader;
use modules\projects\common\models\Project;
use ms\loyalty\finances\backend\models\TransactionSearch;
use ms\loyalty\finances\common\models\Transaction;
use yii\db\ActiveQuery;

/**
 * @inheritDoc
 * @property integer $project_id
 */
class ProjectTransactionSearch extends TransactionSearch
{
    public $project_id;

    public function rules()
    {
        return array_merge(parent::rules(), [
            ['project_id', 'integer'],
        ]);
    }

    /**
     * @return ActiveQuery
     */
    protected function prepareQuery()
    {
        return self::find();
    }

    /**
     * @return ActiveQuery
     */
    public static function find()
    {
        return parent::find()
            ->from(['transaction' => Transaction::tableName()])
            ->joinWith([
                'purse' => function (ActiveQuery $query) {
                    $query->from(['purse' => Purse::tableName()]);
                }
            ])
            ->where(['purse.owner_type' => Project::class]);

    }

    protected function prepareFilters($query)
    {
        $leader = Leader::getLeaderByIdentity();

        if ($leader) {
            if ($leader->roleManager->isAdminLegalPerson()) {
                $query->andWhere(
                    ['purse.project_id' => $leader->legal_person_id]
                );
            }
        }
        $query->andFilterWhere(['purse.project_id' => $this->project_id]);

        parent::prepareFilters($query);
    }
}
