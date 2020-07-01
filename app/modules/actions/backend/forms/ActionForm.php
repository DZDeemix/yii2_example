<?php

namespace modules\actions\backend\forms;

use Yii;
use yii\base\Exception;
use yii2tech\ar\linkmany\LinkManyBehavior;
use marketingsolutions\datetime\DateTimeBehavior;
use modules\actions\common\models\Action;
use modules\actions\common\models\ActionType;
use modules\actions\common\types\ProductsActionType;
use modules\actions\common\types\BonusesAmountActionType;
use modules\actions\common\types\PlanCompleteActionType;
use modules\profiles\common\models\Leader;

/**
 * @property array $groupIds
 * @property array $categoryIds
 * @property array $productIds
 * @property array $regionIds
 * @property array $cityIds
 * @property array $dealerIds
 * @property array $profileIds
 * @property array $adminIds
 */
class ActionForm extends Action
{
    public function rules()
    {
        return array_merge(parent::rules(), [
            ['start_on_local', 'required'],
            ['end_on_local', 'required'],
           // ['olap_period_on_local', 'required'],

          /*  ['pay_threshold', 'required', 'when' => function(ActionForm $model) {
                return $model->pay_type === Action::PAY_TYPE_THRESHOLD;
            },
                'enableClientValidation' => false,
                'message' => 'Необходимо указать порог начисления баллов'
            ],*/

           /* ['bonuses_amount', 'required', 'when' => function(ActionForm $model) {
                return $model->type->className === BonusesAmountActionType::class;
            },
                'enableClientValidation' => false,
                'message' => 'Необходимо указать сумму разовой закупки'
            ],*/

            /*['plan_amount', 'required', 'when' => function(ActionForm $model) {
                return $model->type->className === PlanCompleteActionType::class;
            },
                'enableClientValidation' => false,
                'message' => 'Необходимо указать план'
            ],*/

            ['groupIds', 'safe'],

            ['categoryIds', 'safe'],
            ['personal_plan_formula', 'safe'],

            ['productIds', 'safe'],
            /*['productIds', 'required', 'when' => function(ActionForm $model) {
                $actionType = ActionType::findOne(['id' => $model->type_id]);

                return $actionType->className === ProductsActionType::class;
            },
                'enableClientValidation' => false,
                'message' => 'Необходимо выбрать товары участвующие в акции'
            ],*/

            ['regionIds', 'safe'],

          //  ['cityIds', 'safe'],

           // ['dealerIds', 'safe'],
            ['email_is_send', 'safe'],

            ['profileIds', 'safe'],

            ['confirm_period', 'integer'],

          /*  ['adminIds',
                'required',
                'message' => 'Необходимо выбрать роли, требующиеся для подтверждения продажи/покупки в данной акции'
            ],*/
        ]);
    }

    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), [
            'start_on_local' => 'Дата старта акции',
            'end_on_local' => 'Дата окончания акции',
            //'olap_period_on_local' => 'Дата старта акции',
            'adminIds' => ''
        ]);
    }

    public function behaviors()
    {
        return array_merge(parent::behaviors(), [
            'linkGroupBehavior' => [
                'class' => LinkManyBehavior::class,
                'relation' => 'groups',
                'relationReferenceAttribute' => 'groupIds',
            ],
            'linkCategoryBehavior' => [
                'class' => LinkManyBehavior::class,
                'relation' => 'categories',
                'relationReferenceAttribute' => 'categoryIds',
            ],
            'linkProductBehavior' => [
                'class' => LinkManyBehavior::class,
                'relation' => 'products',
                'relationReferenceAttribute' => 'productIds',
            ],
            'linkRegionBehavior' => [
                'class' => LinkManyBehavior::class,
                'relation' => 'regions',
                'relationReferenceAttribute' => 'regionIds',
            ],
            'linkCityBehavior' => [
                'class' => LinkManyBehavior::class,
                'relation' => 'cities',
                'relationReferenceAttribute' => 'cityIds',
            ],
            'linkDealerBehavior' => [
                'class' => LinkManyBehavior::class,
                'relation' => 'dealers',
                'relationReferenceAttribute' => 'dealerIds',
            ],
            'linkProfileBehavior' => [
                'class' => LinkManyBehavior::class,
                'relation' => 'profiles',
                'relationReferenceAttribute' => 'profileIds',
            ],
            'linkAdminBehavior' => [
                'class' => LinkManyBehavior::class,
                'relation' => 'admins',
                'relationReferenceAttribute' => 'adminIds',
            ],
            'dateTimeBehavior' => [
                'class' => DateTimeBehavior::class,
                'originalFormat' => ['date', 'yyyy-MM-dd'],
                'targetFormat' => ['date', 'dd.MM.yyyy'],
                'attributes' => [
                    'start_on',
                    'end_on',
                ]
            ]
        ]);
    }

    /**
     * @return bool
     */
    public function process()
    {
        if (false === $this->validate()) {
            return false;
        }

        $transaction = self::getDb()->beginTransaction();

        try {

            if ($this->isNewRecord) {
                $this->admin_id = Yii::$app->user->identity->getId();
            }

            $leader = Leader::getLeaderByIdentity();

            if ($leader) {
                $this->processRegions($leader);
//                $this->processProfiles($leader);
            }

            if (false === $this->save(false)) {
                return false;
            }

            $transaction->commit();

            return true;

        } catch (Exception $e) {
            $transaction->rollBack();

            return false;
        }
    }

    /**
     * @param Leader $leader
     * @return bool
     */
    private function processRegions(Leader $leader)
    {
        if (!empty($this->regionIds) && array_search($leader->region_id, $this->regionIds)) {
            return true;
        }

        $regionIds = array_filter((array) $this->regionIds);
        $regionIds[] = $leader->region_id;
        $this->regionIds = $regionIds;

        return true;
    }

    /**
     * @param Leader $leader
     * @return bool
     */
    private function processProfiles(Leader $leader)
    {
        if ($leader->roleManager->isAdminRegional()) {
            return true;
        }

        $leaderProfileIds = $leader->getProfiles()->column();

        $profileIds = array_filter((array) $this->profileIds);

        foreach ($leaderProfileIds as $leaderProfileId) {
            if (array_search($leaderProfileId, $this->profileIds)) {
                continue;
            }

            $profileIds[] = $leaderProfileId;
        }

        $this->profileIds = $profileIds;

        return true;
    }


}