<?php

namespace modules\burnpoints\common\events;

use marketingsolutions\finance\models\Transaction;
use modules\burnpoints\common\finances\NullifyPartner;
use modules\sales\common\finances\SalePartner;
use yii\base\Event;

class TransactionModelEvent extends Event
{
    public static function beforeSaveMethod($event)
    {
        try {
            /** @var Transaction $transaction */
            $transaction = $event->sender;

            if ($transaction->partner_type !== NullifyPartner::class) {
                if ($transaction->type === Transaction::OUTBOUND && $transaction->partner_type !== SalePartner::class) {
                    $transaction->residue = $transaction->amount;
                }
                if ($transaction->type === Transaction::INCOMING && $transaction->partner_type === SalePartner::class) {
                    $transaction->points_to_burn = $transaction->amount;
                }
            }
        }
        catch (\Throwable $e) {
            # нельзя допускать, чтоб код ломал сохранение транзакций в других модулях
        }
    }
}
