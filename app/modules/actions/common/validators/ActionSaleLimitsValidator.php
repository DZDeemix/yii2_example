<?php

namespace modules\actions\common\validators;

use yii\validators\Validator;
use modules\sales\common\models\Sale;
use modules\sales\common\models\SalePosition;
use modules\sales\common\models\SaleAction;
use modules\sales\common\sales\statuses\Statuses;

/**
 * Validates action sales limits for Profile
 * If set $action->limit_bonuses validates action bonuses sum
 * if set $action->limit_qty validates sale positions quantity
 */
class ActionSaleLimitsValidator extends Validator
{
    /**
     * @var Sale
     */
    private $_model;

    /**
     * @var
     */
    private $_profile;

    /**
     * @var
     */
    private $_action;

    /**
     * @param Sale $model
     * @param string $attribute
     * @return bool
     */
    public function validateAttribute($model, $attribute)
    {
        $this->_model = $model;
        $this->_profile = $model->profile;
        $this->_action = $model->action;

        if (null === $this->_profile) {
            $model->addError($attribute, 'Участник не найден');

            return false;
        }

        if (null === $this->_action) {
            $model->addError($attribute, 'Акция не найдена');

            return false;
        }

        if ($this->_action->limit_bonuses) {
            if ($this->_action->limit_bonuses < $this->getBonusesSum()) {
                $model->addError($attribute, 'Превышен лимит начисленных бонусных баллов в акции для участника');

                return false;
            }
        }

        if ($this->_action->limit_qty) {
            if ($this->_action->limit_qty < $this->getPositionsQuantity()) {
                $model->addError($attribute, 'Превышен лимит количества товаров в акции для участника');

                return false;
            }
        }

        return true;
    }

    /**
     * @return int
     */
    private function getBonusesSum()
    {
        $query = SaleAction::find()
            ->joinWith(['sale'])
            ->where([
                'AND',
                ['{{%sales}}.recipient_id' => $this->_profile->id],
                ['{{%sales}}.action_id' => $this->_action->id],
                ['IN', '{{%sales}}.status', [Statuses::APPROVED, Statuses::PAID]]
            ]);

        return (int) $query->sum('{{%sales_actions}}.bonuses');
    }

    /**
     * @return int
     */
    private function getPositionsQuantity()
    {
        $positionsQty = (int) SalePosition::find()
            ->joinWith(['sale'])
            ->where([
                'AND',
                ['{{%sales}}.recipient_id' => $this->_profile->id],
                ['{{%sales}}.action_id' => $this->_action->id],
                ['NOT IN', '{{%sales}}.status', [Statuses::DECLINED]]
            ])
            ->sum('quantity');

        $currentPositionsQty = 0;
        foreach ($this->_model->positions as $position) {
            $currentPositionsQty += (int) $position['quantity'];
        }

        return $positionsQty + $currentPositionsQty;
    }

}