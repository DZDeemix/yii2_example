<?php

namespace modules\sales\common\sales\statuses;

use modules\profiles\common\models\Leader;
use modules\sales\common\models\Sale;
use yii\base\BaseObject;
use yii\base\InvalidCallException;
use yii\db\Expression;


/**
 * Class StatusManager
 */
class StatusManager extends BaseObject
{
    /**
     * @var Sale
     */
    private $sale;

    public function __construct(Sale $sale, $config = [])
    {
        $this->sale = $sale;
        parent::__construct($config);
    }

    public function adminCanSetStatus($status)
    {
        $leader = Leader::getLeaderByIdentity();
        if (!is_null($leader) && (($status == Statuses::PAID) ||$status == Statuses::ROLLBACK )) {
            return false;
        }
        switch ($this->sale->status) {
            case Statuses::ADMIN_REVIEW:
                return in_array($status, [Statuses::APPROVED, Statuses::DECLINED]);
            case Statuses::APPROVED:
                return in_array($status, [Statuses::PAID, Statuses::DECLINED]);
            case Statuses::DECLINED:
                return in_array($status, [Statuses::APPROVED]);
            case Statuses::DRAFT:
                return in_array($status, [Statuses::APPROVED, Statuses::DECLINED]);
            case Statuses::PAID:
                return in_array($status, [Statuses::ROLLBACK]);
        }
        if (!$this->sale->status && is_null($leader)) {
            return true;
        }
        return false;
    }

    public function recipientCanSetStatus($status)
    {
        switch ($this->sale->status) {
            case Statuses::DRAFT:
                return in_array($status, [Statuses::ADMIN_REVIEW]);
        }
        return false;
    }

    /**
     * @param string $status
     * @param string $comment
     * @return bool
     */
    public function changeStatus($status, $comment = null)
    {
        if ($this->sale->isNewRecord) {
            throw new InvalidCallException('Can not change status of the new sale');
        }
        switch ($status) {
            case Statuses::DRAFT:
                $this->sale->updateAttributes(array_merge([
                    'status' => $status,
                    'approved_by_admin_at' => null,
                ], $comment !== null ? ['review_comment' => $comment] : []));
                break;
            case Statuses::ADMIN_REVIEW:
                $this->sale->updateAttributes([
                    'status' => $status,
                    'approved_by_admin_at' => null,
                ]);
                break;
            case Statuses::APPROVED:
                $this->sale->updateAttributes([
                    'status' => $status,
                    'approved_by_admin_at' => new Expression('NOW()'),
                ]);
                break;
            case Statuses::PAID:
                $this->sale->updateAttributes([
                    'status' => $status,
                ]);
                break;
            case Statuses::DECLINED:
                $this->sale->updateAttributes(array_merge([
                    'status' => $status,
                    'approved_by_admin_at' => null,
                ], $comment !== null ? ['review_comment' => $comment] : []));
                break;
            default:
                return false;
        }
        return true;
    }

    public function canBeDeleted()
    {
        return true;
    }

    /**
     * Is user can edit this sale
     * @return bool
     */
    public function recipientCanEdit()
    {
        return $this->sale->status == Statuses::DRAFT;
    }

    /**
     * Is administrator can edit this sale
     * @return bool
     */
    public function adminCanEdit()
    {
        return $this->sale->status == Statuses::ADMIN_REVIEW;
    }
}
